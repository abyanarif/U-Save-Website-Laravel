import { auth } from "./firebase-init.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// --- Budgeting App Object ---
// Mengelompokkan semua logika ke dalam satu objek untuk kerapian
const BudgetingApp = {
    // --- State & Konfigurasi ---
    customCategories: [],
    API_BASE_URL: "/api",
    elements: {}, // Untuk menyimpan elemen DOM yang sering diakses

    // --- Fungsi Utama ---

    /**
     * Mengambil daftar kota dari API dan mengisi dropdown.
     */
    async loadCityOptions() {
        if (!this.elements.asalKotaSelect) return;
        try {
            const response = await fetch(`${this.API_BASE_URL}/cities`);
            if (!response.ok) throw new Error('Gagal memuat data kota.');
            const cities = await response.json();
            
            this.elements.asalKotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
            cities.forEach(city => {
                const option = new Option(city, city);
                this.elements.asalKotaSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error loading cities:", error);
            this.elements.asalKotaSelect.innerHTML = '<option value="">Gagal memuat kota</option>';
        }
    },

    /**
     * Mengambil data budget yang tersimpan milik pengguna.
     */
    async loadUserBudget() {
        const token = await this.getFirebaseToken();
        if (!token) return;

        try {
            const response = await fetch(`${this.API_BASE_URL}/budget`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            
            if (response.status === 404) {
                console.log("Tidak ada budget tersimpan. Siap untuk input baru.");
                this.resetAll();
                return;
            }
            if (!response.ok) throw new Error('Gagal memuat budget tersimpan.');

            const budgetData = await response.json();
            this.populateForm(budgetData);
            this.displayResults(budgetData);
        } catch (error) {
            console.error("Error loading budget:", error);
        }
    },

    /**
     * Mengirim data budget untuk disimpan atau diperbarui.
     */
    async saveBudget() {
        const token = await this.getFirebaseToken();
        if (!token) { alert("Sesi berakhir. Silakan login kembali."); return; }
        
        const uangSaku = parseFloat(this.elements.uangSakuInput.value.replace(/[^\d]/g, "")) || 0;
        const asalKota = this.elements.asalKotaSelect.value;
        if (uangSaku <= 0 || !asalKota) {
            alert("Harap isi Uang Saku dan Asal Kota dengan benar.");
            return;
        }

        const checkedStandardCategories = Array.from(document.querySelectorAll('input[name="budgetCategory"]:checked:not(#lainnya)')).map(el => el.value);
        const totalCustomAmount = this.customCategories.reduce((acc, c) => acc + c.amount, 0);
        const remainingForStandard = uangSaku - totalCustomAmount;
        
        if(remainingForStandard < 0) {
            alert("Total kebutuhan lainnya melebihi uang saku!");
            return;
        }

        const allocations = this.calculateStandardAllocations(remainingForStandard, checkedStandardCategories);
        const itemsPayload = [
            ...checkedStandardCategories.map(cat => ({ category_name: cat, allocated_amount: allocations[cat] || 0, is_custom: false, original_input_amount: null })),
            ...this.customCategories.map(cat => ({ category_name: cat.name, allocated_amount: cat.amount, is_custom: true, original_input_amount: cat.amount }))
        ];
        
        const payload = {
            total_pocket_money: uangSaku,
            city_of_origin: asalKota,
            items: itemsPayload
        };

        try {
            const response = await fetch(`${this.API_BASE_URL}/budget`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.message || 'Gagal menyimpan data.');
            }
            
            const result = await response.json();
            alert('Anggaran berhasil disimpan!');
            this.displayResults(result);
            document.getElementById('cek').scrollIntoView({ behavior: 'smooth' });
        } catch (error) {
            console.error('Error saving budget:', error);
            alert('Terjadi kesalahan: ' + error.message);
        }
    },

    // --- Fungsi-fungsi Pembantu (Helper) ---

    async getFirebaseToken() {
        if (auth.currentUser) {
            try { return await auth.currentUser.getIdToken(true); } catch (e) { console.error("Error getting token:", e); return null; }
        }
        return null;
    },

    formatNumber(num) {
        return (parseFloat(num) || 0).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    
    populateForm(userData) {
        this.elements.uangSakuInput.value = `Rp. ${this.formatNumber(userData.monthly_pocket_money || userData.total_pocket_money)}`;
        this.elements.asalKotaSelect.value = userData.city_of_origin;
        
        document.querySelectorAll('input[name="budgetCategory"]:checked').forEach(cb => cb.checked = false);
        this.customCategories = [];
        
        const items = userData.items || userData.budget_items || [];
        items.forEach(item => {
            if (item.is_custom) {
                this.customCategories.push({ id: item.id || Date.now(), name: item.category_name, amount: parseFloat(item.original_input_amount) });
            } else {
                const checkbox = document.getElementById(item.category_name.toLowerCase());
                if (checkbox) checkbox.checked = true;
            }
        });
        this.renderCustomCategoriesList();
    },

    displayResults(data) {
        const pocketMoney = data.monthly_pocket_money || data.total_pocket_money;
        const items = data.items || data.budget_items || [];
        
        if (this.elements.uangSakuDisplay.span) this.elements.uangSakuDisplay.span.textContent = this.formatNumber(pocketMoney);
        if (this.elements.uangSakuDisplay.message) this.elements.uangSakuDisplay.message.style.display = 'none';

        const map = {
            kos: { spanId: "tampilJumlahTempatTinggal", boxId: "uang-tempat-tinggal-display", msg: "Biaya kos/akomodasi" },
            makan: { spanId: "tampilJumlahMakan", boxId: "uang-makan-display", msg: "Biaya makan bulanan" },
            hiburan: { spanId: "tampilJumlahHiburan", boxId: "uang-hiburan-display", msg: "Alokasi hiburan" },
            internet: { spanId: "tampilJumlahInternet", boxId: "uang-internet-display", msg: "Biaya internet" },
            darurat: { spanId: "tampilJumlahDanaDarurat", boxId: "dana-darurat-display", msg: "Tabungan darurat" }
        };

        Object.values(map).forEach(m => {
            const spanEl = document.getElementById(m.spanId);
            const msgEl = document.querySelector(`#${m.boxId} .hasil-pesan`);
            if (spanEl) spanEl.textContent = "0";
            if (msgEl) msgEl.textContent = "Tidak dialokasikan.";
        });
        
        document.getElementById("custom-categories-results-container")?.remove();
        const customItemsToShow = [];
        items.forEach(item => {
            const mapInfo = map[item.category_name];
            if (mapInfo) {
                document.getElementById(mapInfo.spanId).textContent = this.formatNumber(item.allocated_amount);
                document.querySelector(`#${mapInfo.boxId} .hasil-pesan`).textContent = mapInfo.msg;
            } else if (item.is_custom) {
                customItemsToShow.push({ name: item.category_name, amount: parseFloat(item.allocated_amount) });
            }
        });
        
        if (customItemsToShow.length > 0) {
             const cekWrapper = document.getElementById("cekWrapper");
             if(cekWrapper) {
                const customContainer = document.createElement("div");
                customContainer.className = "results-grid"; // Menggunakan grid untuk layout dinamis
                customContainer.id = "custom-categories-results-container";
                customItemsToShow.forEach(item => {
                    const box = document.createElement('div');
                    box.className = 'hasil-box custom-box';
                    box.innerHTML = `<h3 class="hasil-title">${item.name}</h3><div class="hasil-amount">Rp <span>${this.formatNumber(item.amount)}</span></div><p class="hasil-pesan">Kebutuhan tambahan.</p>`;
                    customContainer.appendChild(box);
                });
                cekWrapper.appendChild(customContainer);
             }
        }
    },

    resetAll() {
        this.elements.budgetingForm.reset();
        this.customCategories = [];
        this.renderCustomCategoriesList();
        this.resetDisplayResults();
    },
    
    resetDisplayResults() {
        if(this.elements.uangSakuDisplay.span) this.elements.uangSakuDisplay.span.textContent = "0";
        if(this.elements.uangSakuDisplay.message) this.elements.uangSakuDisplay.message.style.display = 'block';
        // ... (Logika lain untuk mereset semua kotak hasil menjadi 0) ...
    },

    calculateStandardAllocations(budget, categories) {
        const weights = { kos: 1.2, makan: 1.2, hiburan: 0.7, internet: 0.5, darurat: 0.6 };
        const totalWeight = categories.reduce((sum, cat) => sum + (weights[cat] || 0), 0);
        const allocations = {};
        if (totalWeight > 0 && budget > 0) {
            categories.forEach(cat => { allocations[cat] = (budget * weights[cat]) / totalWeight; });
        }
        return allocations;
    },
    
    renderCustomCategoriesList() {
        if (!this.elements.customListDiv) return;
        this.elements.customListDiv.innerHTML = "";
        if (this.customCategories.length === 0) {
            this.elements.customListDiv.innerHTML = '<p class="no-categories">Belum ada kebutuhan lainnya.</p>';
            if(this.elements.confirmCategoriesBtn) this.elements.confirmCategoriesBtn.disabled = true;
            return;
        }
        if(this.elements.confirmCategoriesBtn) this.elements.confirmCategoriesBtn.disabled = false;
        
        const listHeader = document.createElement("h4");
        listHeader.textContent = "Kebutuhan yang Ditambahkan:";
        this.elements.customListDiv.appendChild(listHeader);

        this.customCategories.forEach(category => {
            const item = document.createElement("div");
            item.className = "custom-category-item";
            item.innerHTML = `<span>${category.name}: Rp ${this.formatNumber(category.amount)}</span> <button data-id="${category.id}" class="delete-custom-btn">&times;</button>`;
            this.elements.customListDiv.appendChild(item);
        });
    },

    setupEventListeners() {
        this.elements.uangSakuInput?.addEventListener("keyup", (e) => this.handleUangSakuInput(e));
        this.elements.saveButton?.addEventListener("click", () => this.saveBudget());
        
        if (this.elements.modal && this.elements.openLainnyaModalBtn) {
            this.elements.openLainnyaModalBtn.addEventListener("click", () => this.toggleModal(true));
            this.elements.modal.querySelector(".close")?.addEventListener("click", () => this.toggleModal(false));
            this.elements.confirmCategoriesBtn?.addEventListener("click", () => this.toggleModal(false));

            this.elements.customCategoryForm?.addEventListener("submit", (e) => this.addCustomCategory(e));
            this.elements.customListDiv?.addEventListener('click', (e) => this.deleteCustomCategory(e));
        }
    },
    
    handleUangSakuInput(e) {
        let value = e.target.value.replace(/\D/g, "");
        e.target.value = value ? "Rp. " + this.formatNumber(value) : "";
        if (this.elements.uangSakuDisplay.span) {
            this.elements.uangSakuDisplay.span.textContent = this.formatNumber(value);
            if(this.elements.uangSakuDisplay.message) {
                 this.elements.uangSakuDisplay.message.style.display = value ? 'none' : 'block';
            }
        }
    },
    
    toggleModal(show) {
        if (this.elements.modal) {
            this.elements.modal.style.display = show ? "block" : "none";
            if (show) this.renderCustomCategoriesList();
        }
    },
    
    addCustomCategory(e) {
        e.preventDefault();
        const name = this.elements.customCategoryNameInput.value.trim();
        const amount = parseFloat(this.elements.customCategoryAmountInput.value);
        if (name && amount > 0) {
             if (this.customCategories.some(c => c.name.toLowerCase() === name.toLowerCase())) {
                alert("Kategori ini sudah ada.");
                return;
            }
            this.customCategories.push({ id: Date.now(), name, amount });
            this.renderCustomCategoriesList();
            this.elements.customCategoryForm.reset();
            this.elements.customCategoryNameInput.focus();
        } else {
            alert("Nama dan nominal harus diisi dengan benar.");
        }
    },
    
    deleteCustomCategory(e) {
        if (e.target.classList.contains('delete-custom-btn')) {
            const idToDelete = e.target.dataset.id;
            this.customCategories = this.customCategories.filter(cat => cat.id.toString() !== idToDelete);
            this.renderCustomCategoriesList();
        }
    },
    
    // FIX: Menambahkan metode cacheElements yang hilang
    cacheElements() {
        this.elements = {
            budgetingForm: document.getElementById("budgetingForm"),
            asalKotaSelect: document.getElementById("asalKota"),
            uangSakuInput: document.getElementById("uangSakuInput"),
            saveButton: document.getElementById("saveBudgetButton"),
            cekWrapper: document.getElementById("cekWrapper"),
            uangSakuDisplay: {
                span: document.getElementById("tampilJumlahUangSaku"),
                message: document.querySelector("#uang-saku-display .hasil-pesan")
            },
            modal: document.getElementById("customCategoryModal"),
            openLainnyaModalBtn: document.getElementById("openLainnyaModalBtn"), // Menggunakan ID tombol
            customListDiv: document.getElementById("customCategoriesList"),
            customCategoryForm: document.getElementById("customCategoryForm"),
            customCategoryNameInput: document.getElementById("customCategoryName"),
            customCategoryAmountInput: document.getElementById("customCategoryAmount"),
            confirmCategoriesBtn: document.getElementById("confirmCategories"),
        };
    },

    init() {
        this.cacheElements(); // Panggil cacheElements terlebih dahulu
        this.setupEventListeners();
        this.loadCityOptions();

        onAuthStateChanged(auth, (user) => {
            if (user) {
                console.log("User authenticated. Loading budget...");
                this.loadUserBudget();
            } else {
                console.log("User not authenticated.");
            }
        });
    }
};

document.addEventListener("DOMContentLoaded", () => {
    BudgetingApp.init();
});
