import { auth } from "./firebase-init.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// --- Global variables ---
let customCategories = [];
const API_BASE_URL = "/api";

// --- Fungsi Format Rupiah ---
document.querySelectorAll(".rupiah-input").forEach((input) => {
    input.addEventListener("keyup", function (e) {
        let value = this.value.replace(/\D/g, "");
        this.value = value
            ? "Rp. " + new Intl.NumberFormat("id-ID").format(value)
            : "";
    });
});

// --- Fungsi-fungsi Utama ---

async function loadCityOptions() {
    const asalKotaSelect = document.getElementById("asalKota");
    try {
        const response = await fetch(`${API_BASE_URL}/cities`);
        if (!response.ok) throw new Error("Gagal memuat data kota.");
        const cities = await response.json();

        asalKotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        cities.forEach((city) => {
            const option = document.createElement("option");
            option.value = city;
            option.textContent = city;
            asalKotaSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error loading cities:", error);
        asalKotaSelect.innerHTML =
            '<option value="">Gagal memuat kota</option>';
    }
}

async function loadUserBudget() {
    const token = await getFirebaseToken();
    if (!token) return;

    try {
        const response = await fetch(`${API_BASE_URL}/budget`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });

        if (response.status === 404) {
            console.log(
                "Tidak ada budget yang tersimpan. Halaman siap untuk input baru."
            );
            resetDisplayResults();
            document.getElementById("budgetingForm").reset();
            return;
        }
        if (!response.ok) throw new Error("Gagal memuat budget tersimpan.");

        const budgetData = await response.json();
        populateFormWithBudgetData(budgetData);
        displayResultsFromServer(budgetData);
    } catch (error) {
        console.error("Error loading budget:", error);
    }
}

async function saveBudget() {
    const token = await getFirebaseToken();
    if (!token) {
        alert("Sesi berakhir. Silakan login kembali.");
        return;
    }

    const uangSaku =
        parseFloat(
            document.getElementById("uangSakuInput").value.replace(/[^\d]/g, "")
        ) || 0;
    const asalKota = document.getElementById("asalKota").value;
    if (uangSaku <= 0 || !asalKota) {
        alert("Harap isi Uang Saku dan Asal Kota dengan benar.");
        return;
    }

    const checkedStandardCategories = Array.from(
        document.querySelectorAll(
            'input[name="budgetCategory"]:checked:not(#lainnya)'
        )
    ).map((el) => el.value);
    const totalCustomAmount = customCategories.reduce(
        (acc, c) => acc + c.amount,
        0
    );
    const remainingBudgetForStandard = uangSaku - totalCustomAmount;

    if (remainingBudgetForStandard < 0) {
        alert("Total kebutuhan lainnya melebihi uang saku!");
        return;
    }

    const allocations = calculateStandardAllocations(
        remainingBudgetForStandard,
        checkedStandardCategories
    );
    const itemsPayload = [];
    checkedStandardCategories.forEach((cat) => {
        itemsPayload.push({
            category_name: cat,
            allocated_amount: allocations[cat] || 0,
            is_custom: false,
            original_input_amount: null,
        });
    });
    customCategories.forEach((cat) => {
        itemsPayload.push({
            category_name: cat.name,
            allocated_amount: cat.amount,
            is_custom: true,
            original_input_amount: cat.amount,
        });
    });

    const payload = {
        total_pocket_money: uangSaku,
        city_of_origin: asalKota,
        items: itemsPayload,
    };

    try {
        const response = await fetch(`${API_BASE_URL}/budget`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const errData = await response.json();
            throw new Error(errData.message || "Gagal menyimpan data.");
        }

        const result = await response.json();
        alert("Anggaran berhasil disimpan!");
        displayResultsFromServer(result);
        document.getElementById("cek").scrollIntoView({ behavior: "smooth" });
    } catch (error) {
        console.error("Error saving budget:", error);
        alert("Terjadi kesalahan: " + error.message);
    }
}

// --- Fungsi-fungsi Pembantu (Helper) ---

async function getFirebaseToken() {
    if (auth.currentUser) {
        try {
            return await auth.currentUser.getIdToken(true);
        } catch (error) {
            console.error("Error getting token:", error);
            return null;
        }
    }
    return null;
}

function formatNumber(num) {
    return (parseFloat(num) || 0)
        .toFixed(0)
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function populateFormWithBudgetData(budgetData) {
    document.getElementById("uangSakuInput").value = `Rp. ${formatNumber(
        budgetData.monthly_pocket_money || budgetData.total_pocket_money
    )}`;
    document.getElementById("asalKota").value = budgetData.city_of_origin;

    document
        .querySelectorAll('input[name="budgetCategory"]:not(#lainnya)')
        .forEach((cb) => (cb.checked = false));
    customCategories = [];

    const items = budgetData.items || budgetData.budget_items || [];
    if (items.length > 0) {
        items.forEach((item) => {
            if (item.is_custom) {
                customCategories.push({
                    id: item.id || Date.now(),
                    name: item.category_name,
                    amount: parseFloat(item.original_input_amount),
                });
            } else {
                const checkbox = document.getElementById(
                    item.category_name.toLowerCase()
                );
                if (checkbox) checkbox.checked = true;
            }
        });
    }
    document.getElementById("lainnya").checked = customCategories.length > 0;
    renderCustomCategoriesList();
}

function displayResultsFromServer(data) {
    const budgetPlan = data.budgetPlan || data;
    const pocketMoney =
        budgetPlan.monthly_pocket_money || budgetPlan.total_pocket_money;
    const items = budgetPlan.items || budgetPlan.budget_items || [];

    document.getElementById("tampilJumlahUangSaku").textContent =
        formatNumber(pocketMoney);
    const standardDisplayMap = {
        kos: {
            spanId: "tampilJumlahTempatTinggal",
            boxId: "uang-tempat-tinggal-display",
            msg: "Biaya kos/akomodasi",
        },
        makan: {
            spanId: "tampilJumlahMakan",
            boxId: "uang-makan-display",
            msg: "Biaya makan bulanan",
        },
        hiburan: {
            spanId: "tampilJumlahHiburan",
            boxId: "uang-hiburan-display",
            msg: "Alokasi hiburan",
        },
        internet: {
            spanId: "tampilJumlahInternet",
            boxId: "uang-internet-display",
            msg: "Biaya internet",
        },
        darurat: {
            spanId: "tampilJumlahDanaDarurat",
            boxId: "dana-darurat-display",
            msg: "Tabungan darurat",
        },
    };
    Object.values(standardDisplayMap).forEach((m) => {
        document.getElementById(m.spanId).textContent = "0";
        document.querySelector(`#${m.boxId} .hasil-pesan`).textContent =
            "Tidak dialokasikan.";
    });

    document.getElementById("custom-categories-results-container")?.remove();
    const customItemsToShow = [];
    items.forEach((item) => {
        if (item.is_custom) {
            customItemsToShow.push({
                name: item.category_name,
                amount: parseFloat(item.allocated_amount),
            });
        } else {
            const mapInfo = standardDisplayMap[item.category_name];
            if (mapInfo) {
                document.getElementById(mapInfo.spanId).textContent =
                    formatNumber(item.allocated_amount);
                document.querySelector(
                    `#${mapInfo.boxId} .hasil-pesan`
                ).textContent = mapInfo.msg;
            }
        }
    });

    if (customItemsToShow.length > 0) {
        const cekWrapper = document.getElementById("cekWrapper");
        const customContainer = document.createElement("div");
        customContainer.className = "hasil-container custom-results";
        customContainer.id = "custom-categories-results-container";
        let customHTML =
            '<h2 class="custom-header">Kebutuhan Tambahan Lainnya</h2>';
        customItemsToShow.forEach((item) => {
            customHTML += `
                <div class="hasil-box custom-box">
                    <h3 class="hasil-title">${item.name}</h3>
                    <div class="hasil-amount">Rp <span>${formatNumber(
                        item.amount
                    )}</span></div>
                    <p class="hasil-pesan">Kebutuhan tambahan.</p>
                </div>`;
        });
        customContainer.innerHTML = customHTML;
        if (cekWrapper) cekWrapper.appendChild(customContainer);
    }
}

function resetDisplayResults() {
    document.getElementById("tampilJumlahUangSaku").textContent = "0";
    const standardDisplayMap = {
        kos: {
            spanId: "tampilJumlahTempatTinggal",
            boxId: "uang-tempat-tinggal-display",
        },
        makan: { spanId: "tampilJumlahMakan", boxId: "uang-makan-display" },
        hiburan: {
            spanId: "tampilJumlahHiburan",
            boxId: "uang-hiburan-display",
        },
        internet: {
            spanId: "tampilJumlahInternet",
            boxId: "uang-internet-display",
        },
        darurat: {
            spanId: "tampilJumlahDanaDarurat",
            boxId: "dana-darurat-display",
        },
    };
    Object.values(standardDisplayMap).forEach((m) => {
        if (document.getElementById(m.spanId)) {
            document.getElementById(m.spanId).textContent = "0";
            document.querySelector(`#${m.boxId} .hasil-pesan`).textContent =
                "Tidak dialokasikan.";
        }
    });
    document.getElementById("custom-categories-results-container")?.remove();
}

function calculateStandardAllocations(budget, categories) {
    const weights = {
        kos: 1.2,
        makan: 1.2,
        hiburan: 0.7,
        internet: 0.5,
        darurat: 0.6,
    };
    const totalWeight = categories.reduce(
        (sum, cat) => sum + (weights[cat] || 0),
        0
    );
    const allocations = {};
    if (totalWeight > 0 && budget > 0) {
        categories.forEach((cat) => {
            allocations[cat] = (budget * weights[cat]) / totalWeight;
        });
    }
    return allocations;
}

function renderCustomCategoriesList() {
    const customListDiv = document.getElementById("customCategoriesList");
    const confirmBtn = document.getElementById("confirmCategories");

    if (!customListDiv || !confirmBtn) return;

    customListDiv.innerHTML = "";
    if (customCategories.length === 0) {
        customListDiv.innerHTML =
            '<p class="no-categories">Belum ada kebutuhan lainnya.</p>';
        confirmBtn.disabled = true;
        return;
    }

    confirmBtn.disabled = false;
    const listHeader = document.createElement("h4");
    listHeader.textContent = "Kebutuhan yang Ditambahkan:";
    customListDiv.appendChild(listHeader);

    customCategories.forEach((category) => {
        const item = document.createElement("div");
        item.className = "custom-category-item";
        item.innerHTML = `<span>${category.name}: Rp ${formatNumber(
            category.amount
        )}</span> <button data-id="${
            category.id
        }" class="delete-custom-btn">&times;</button>`;
        customListDiv.appendChild(item);
    });
}

// --- Inisialisasi Saat Halaman Dimuat ---
document.addEventListener("DOMContentLoaded", function () {
    console.log("Budgeting page loaded.");
    loadCityOptions();

    onAuthStateChanged(auth, (user) => {
        if (user) {
            console.log("User authenticated. Loading budget...");
            loadUserBudget();
        } else {
            console.log("User not authenticated.");
        }
    });

    document
        .getElementById("saveBudgetButton")
        ?.addEventListener("click", saveBudget);

    // Logika untuk Modal "Lainnya"
    const modal = document.getElementById("customCategoryModal");
    const lainnyaCheckbox = document.getElementById("lainnya");
    if (!modal || !lainnyaCheckbox) return;

    lainnyaCheckbox.addEventListener("change", function () {
        modal.style.display = this.checked ? "block" : "none";
        if (this.checked) renderCustomCategoriesList();
    });

    modal.querySelector(".close")?.addEventListener("click", () => {
        modal.style.display = "none";
    });
    modal.querySelector("#confirmCategories")?.addEventListener("click", () => {
        modal.style.display = "none";
    });

    modal
        .querySelector("#customCategoryForm")
        ?.addEventListener("submit", (e) => {
            e.preventDefault();
            const nameInput = document.getElementById("customCategoryName");
            const amountInput = document.getElementById("customCategoryAmount");
            const name = nameInput.value.trim();
            const amount = parseFloat(amountInput.value);

            if (name && amount > 0) {
                if (
                    customCategories.some(
                        (cat) => cat.name.toLowerCase() === name.toLowerCase()
                    )
                ) {
                    alert("Kategori ini sudah ada.");
                    return;
                }
                customCategories.push({ id: Date.now(), name, amount });
                renderCustomCategoriesList();
                nameInput.value = "";
                amountInput.value = "";
                nameInput.focus();
            } else {
                alert("Nama dan nominal harus diisi dengan benar.");
            }
        });

    document
        .getElementById("customCategoriesList")
        ?.addEventListener("click", (e) => {
            if (e.target.classList.contains("delete-custom-btn")) {
                const idToDelete = e.target.dataset.id;
                customCategories = customCategories.filter(
                    (cat) => cat.id.toString() !== idToDelete
                );
                renderCustomCategoriesList();
            }
        });
});
