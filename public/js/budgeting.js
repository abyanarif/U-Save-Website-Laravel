// Impor instance auth dari firebase-init.js
import { auth } from "./firebase-init.js"; // Sesuaikan path jika perlu
// Impor fungsi onAuthStateChanged dari Firebase SDK
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// --- Input Rupiah Formatting ---
document.querySelectorAll(".rupiah-input").forEach((input) => {
    input.addEventListener("keyup", function (e) {
        let value = this.value.replace(/\D/g, "");
        if (value === "") {
            this.value = "";
            return;
        }
        value = new Intl.NumberFormat("id-ID").format(value);
        this.value = "Rp. " + value;
    });
    input.addEventListener("blur", function (e) {
        let value = this.value.replace(/\D/g, "");
        if (value) {
            value = new Intl.NumberFormat("id-ID").format(value);
            this.value = "Rp. " + value;
        } else {
            this.value = "";
        }
    });
});

// Global variables
let customCategories = [];
const API_BASE_URL = "/api"; // Sesuaikan dengan base URL API Laravel Anda

// --- Fungsi untuk memuat opsi kota dari variabel global ---
function loadCityOptions() {
    // Tidak perlu async jika hanya baca variabel global
    const asalKotaSelect = document.getElementById("asalKota");
    // Hapus opsi lama kecuali yang pertama (-- Pilih Kota --)
    while (asalKotaSelect.options.length > 1) {
        asalKotaSelect.remove(1);
    }

    const cities = window.citiesForDropdown || []; // Ambil data dari variabel global

    if (Array.isArray(cities) && cities.length > 0) {
        cities.forEach((cityData) => {
            const option = document.createElement("option");
            if (
                typeof cityData === "object" &&
                cityData.hasOwnProperty("nama_kota")
            ) {
                // Jika controller mengirim objek {id: ..., nama_kota: ...}
                // Anda bisa menggunakan cityData.id sebagai value jika backend memerlukannya saat save.
                // Untuk konsistensi dengan city_of_origin yang string, kita gunakan nama_kota.
                option.value = cityData.nama_kota;
                option.textContent = cityData.nama_kota;
            } else if (typeof cityData === "string") {
                // Jika controller mengirim array string nama kota
                option.value = cityData;
                option.textContent = cityData;
            }
            // Hanya tambahkan jika ada value dan text yang valid
            if (option.value && option.textContent) {
                asalKotaSelect.appendChild(option);
            }
        });
    } else {
        console.warn(
            "No city options provided from server or data is not an array."
        );
        // Anda bisa menambahkan opsi fallback di sini jika mau, misal:
        // const fallbackOption = document.createElement("option");
        // fallbackOption.value = "Surabaya";
        // fallbackOption.textContent = "Surabaya (Default)";
        // asalKotaSelect.appendChild(fallbackOption);
    }
}

// --- Fungsi untuk interaksi dengan API Laravel ---
async function getFirebaseToken() {
    if (auth.currentUser) {
        try {
            return await auth.currentUser.getIdToken(true);
        } catch (error) {
            console.error("Error getting Firebase token:", error);
            alert("Sesi Anda mungkin telah berakhir. Silakan login kembali.");
            return null;
        }
    }
    console.warn("getFirebaseToken called but auth.currentUser is null.");
    return null;
}

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

async function loadUserBudget() {
    const token = await getFirebaseToken();
    if (!token) {
        console.log(
            "User not authenticated or token unavailable. Cannot load budget."
        );
        resetDisplayResults();
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/budget`, {
            method: "GET",
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });

        if (response.ok) {
            const budgetApiResponse = await response.json();
            const budgetData = budgetApiResponse.data || budgetApiResponse;
            if (
                budgetData &&
                Object.keys(budgetData).length > 0 &&
                budgetData.total_pocket_money !== undefined
            ) {
                populateFormWithBudgetData(budgetData);
                displayResultsFromServer(budgetData);
            } else {
                console.log("No existing budget found or empty data object.");
                resetDisplayResults();
                document.getElementById("budgetingForm").reset();
                // Dropdown kota seharusnya sudah dimuat oleh DOMContentLoaded
                const asalKotaSelect = document.getElementById("asalKota");
                if (asalKotaSelect.options.length > 0)
                    asalKotaSelect.value = "";

                customCategories = [];
                renderCustomCategoriesList();
                document.getElementById("lainnya").checked = false;
            }
        } else if (response.status === 404) {
            console.log("No budget plan found for this user (404).");
            resetDisplayResults();
            document.getElementById("budgetingForm").reset();
            // Dropdown kota seharusnya sudah dimuat oleh DOMContentLoaded
            const asalKotaSelect = document.getElementById("asalKota");
            if (asalKotaSelect.options.length > 0) asalKotaSelect.value = "";

            customCategories = [];
            renderCustomCategoriesList();
            document.getElementById("lainnya").checked = false;
        } else {
            const errorText = await response.text();
            console.error("Failed to load budget:", response.status, errorText);
            alert("Gagal memuat anggaran. Coba lagi nanti.");
            resetDisplayResults();
        }
    } catch (error) {
        console.error("Error loading budget:", error);
        alert("Terjadi kesalahan saat memuat anggaran.");
        resetDisplayResults();
    }
}

function populateFormWithBudgetData(budgetData) {
    const uangSakuInput = document.getElementById("uangSakuInput");
    const asalKotaSelect = document.getElementById("asalKota");

    if (budgetData.total_pocket_money != null) {
        uangSakuInput.value = `Rp. ${formatNumber(
            budgetData.total_pocket_money
        )}`;
    } else {
        uangSakuInput.value = "";
    }

    // asalKotaSelect seharusnya sudah terisi oleh loadCityOptions()
    if (budgetData.city_of_origin) {
        // Cek apakah kota dari budget ada di dropdown
        let cityExists = false;
        for (let i = 0; i < asalKotaSelect.options.length; i++) {
            if (asalKotaSelect.options[i].value === budgetData.city_of_origin) {
                cityExists = true;
                break;
            }
        }
        if (cityExists) {
            asalKotaSelect.value = budgetData.city_of_origin;
        } else {
            console.warn(
                `Kota "${budgetData.city_of_origin}" dari budget tersimpan tidak ditemukan di dropdown. Biarkan default/kosong.`
            );
            asalKotaSelect.value = ""; // Atau pilih opsi default jika ada
        }
    } else {
        asalKotaSelect.value = "";
    }

    document
        .querySelectorAll('input[name="budgetCategory"]:not(#lainnya)')
        .forEach((cb) => (cb.checked = false));
    customCategories = [];

    if (budgetData.items && budgetData.items.length > 0) {
        budgetData.items.forEach((item) => {
            if (item.is_custom) {
                customCategories.push({
                    id: item.id || Date.now().toString() + Math.random(),
                    name: item.category_name,
                    amount: parseFloat(item.original_input_amount),
                });
            } else {
                const checkbox = document.getElementById(
                    item.category_name.toLowerCase()
                );
                if (checkbox) {
                    checkbox.checked = true;
                }
            }
        });
    }

    const lainnyaCheckbox = document.getElementById("lainnya");
    lainnyaCheckbox.checked = customCategories.length > 0;
    renderCustomCategoriesList();
}

async function saveBudget() {
    const token = await getFirebaseToken();
    if (!token) {
        alert(
            "Anda belum login atau sesi telah berakhir. Silakan login terlebih dahulu."
        );
        return;
    }

    const csrfToken = getCsrfToken();
    const uangSakuInput = document.getElementById("uangSakuInput");
    const uangSaku = parseFloat(uangSakuInput.value.replace(/[^\d]/g, "")) || 0;
    const asalKota = document.getElementById("asalKota").value;

    if (uangSaku <= 0) {
        alert("Masukkan jumlah uang saku yang valid!");
        return;
    }
    if (!asalKota) {
        alert("Pilih asal kota Anda!");
        return;
    }

    const checkedStandardCategories = Array.from(
        document.querySelectorAll(
            'input[name="budgetCategory"]:checked:not(#lainnya)'
        )
    ).map((el) => el.value);

    if (
        checkedStandardCategories.length === 0 &&
        customCategories.length === 0
    ) {
        alert(
            "Pilih minimal satu kategori alokasi atau tambahkan kebutuhan lainnya."
        );
        return;
    }

    const lainnyaCheckbox = document.getElementById("lainnya");
    if (lainnyaCheckbox.checked && customCategories.length === 0) {
        if (
            !confirm(
                'Anda memilih "Lainnya" tapi belum menambahkan kebutuhan. Lanjutkan tanpa kebutuhan tambahan atau batalkan untuk mengisi?'
            )
        ) {
            return;
        }
    }

    const budgetPayloadItems = [];
    let remainingBudgetForStandard = uangSaku;
    if (customCategories.length > 0) {
        const totalCustomAmount = customCategories.reduce(
            (sum, cat) => sum + cat.amount,
            0
        );
        remainingBudgetForStandard -= totalCustomAmount;
        if (remainingBudgetForStandard < 0) {
            alert("Total kebutuhan lainnya melebihi uang saku!");
            return;
        }
    }

    if (
        checkedStandardCategories.length > 0 &&
        remainingBudgetForStandard >= 0
    ) {
        const allocations = calculateStandardAllocations(
            remainingBudgetForStandard,
            checkedStandardCategories
        );
        checkedStandardCategories.forEach((catName) => {
            budgetPayloadItems.push({
                category_name: catName,
                allocated_amount: allocations[catName] || 0,
                is_custom: false,
                original_input_amount: null,
            });
        });
    } else if (
        checkedStandardCategories.length > 0 &&
        remainingBudgetForStandard < 0
    ) {
        checkedStandardCategories.forEach((catName) => {
            budgetPayloadItems.push({
                category_name: catName,
                allocated_amount: 0,
                is_custom: false,
                original_input_amount: null,
            });
        });
    }

    customCategories.forEach((customCat) => {
        budgetPayloadItems.push({
            category_name: customCat.name,
            allocated_amount: customCat.amount,
            is_custom: true,
            original_input_amount: customCat.amount,
        });
    });

    const payload = {
        total_pocket_money: uangSaku,
        city_of_origin: asalKota,
        items: budgetPayloadItems,
    };

    try {
        const response = await fetch(`${API_BASE_URL}/budget`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(payload),
        });

        const resultText = await response.text();
        let result;
        try {
            result = JSON.parse(resultText);
        } catch (e) {
            console.error(
                "Failed to parse server response as JSON:",
                resultText
            );
            alert(
                `Gagal menyimpan anggaran: Respons server tidak valid.\nServer: ${resultText.substring(
                    0,
                    200
                )}`
            );
            return;
        }

        if (response.ok) {
            alert("Anggaran berhasil disimpan!");
            const savedData = result.data || result;
            if (savedData && Object.keys(savedData).length > 0) {
                displayResultsFromServer(savedData);
            }
            document
                .getElementById("cek")
                .scrollIntoView({ behavior: "smooth" });
        } else {
            console.error("Failed to save budget:", response.status, result);
            alert(
                `Gagal menyimpan anggaran: ${
                    result.message || "Error tidak diketahui dari server."
                }`
            );
        }
    } catch (error) {
        console.error("Error saving budget:", error);
        alert("Terjadi kesalahan jaringan saat menyimpan anggaran.");
    }
}

// --- Event Listener DOMContentLoaded ---
document.addEventListener("DOMContentLoaded", function () {
    // Tidak perlu async di sini jika loadCityOptions tidak async
    console.log("DOMContentLoaded in budgeting.js fired.");

    // Panggil loadCityOptions untuk mengisi dropdown SEGERA saat DOM siap
    loadCityOptions();

    if (!auth) {
        console.error(
            "Firebase Auth instance is not available from firebase-init.js!"
        );
        alert(
            "Kesalahan konfigurasi Firebase Auth. Aplikasi mungkin tidak berfungsi."
        );
        return;
    }

    onAuthStateChanged(auth, (user) => {
        const asalKotaSelect = document.getElementById("asalKota"); // Akses di sini
        if (user) {
            console.log(
                "User is authenticated (onAuthStateChanged). UID:",
                user.uid
            );
            loadUserBudget(); // Dropdown kota seharusnya sudah terisi
        } else {
            console.log(
                "User is not authenticated (onAuthStateChanged). authGuard should handle redirection."
            );
            resetDisplayResults();
            document.getElementById("budgetingForm").reset();
            if (asalKotaSelect && asalKotaSelect.options.length > 0) {
                // Pastikan elemen ada sebelum diakses
                asalKotaSelect.value = ""; // Reset dropdown kota ke default
            }
            customCategories = [];
            renderCustomCategoriesList();
            document.getElementById("lainnya").checked = false;
        }
    });

    const modal = document.getElementById("customCategoryModal");
    const lainnyaCheckbox = document.getElementById("lainnya");
    const closeBtn = modal.querySelector(".close");
    const addCustomCategoryBtn = document.getElementById("addCustomCategory");
    const confirmCategoriesBtn = document.getElementById("confirmCategories");
    const customCategoryNameInput =
        document.getElementById("customCategoryName");
    const customCategoryAmountInput = document.getElementById(
        "customCategoryAmount"
    );

    lainnyaCheckbox.addEventListener("change", function () {
        if (this.checked) {
            modal.style.display = "block";
            renderCustomCategoriesList();
        } else {
            if (customCategories.length > 0) {
                if (
                    confirm(
                        "Membatalkan centang 'Lainnya' akan menghapus semua kebutuhan tambahan yang sudah Anda masukkan dari FORM saat ini. Perubahan tidak akan disimpan ke server sampai Anda klik 'Hitung & Simpan Anggaran'. Lanjutkan?"
                    )
                ) {
                    customCategories = [];
                    renderCustomCategoriesList();
                } else {
                    this.checked = true;
                }
            }
        }
    });

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
        if (customCategories.length === 0) {
            lainnyaCheckbox.checked = false;
        }
    });

    confirmCategoriesBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    addCustomCategoryBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const name = customCategoryNameInput.value.trim();
        const amount = parseFloat(customCategoryAmountInput.value);

        if (name && !isNaN(amount) && amount > 0) {
            if (
                customCategories.some(
                    (cat) => cat.name.toLowerCase() === name.toLowerCase()
                )
            ) {
                alert("Kebutuhan ini sudah ada!");
                return;
            }
            customCategories.push({
                id: Date.now().toString() + Math.random(),
                name: name,
                amount: amount,
            });
            customCategoryNameInput.value = "";
            customCategoryAmountInput.value = "";
            renderCustomCategoriesList();
            customCategoryNameInput.focus();
        } else {
            alert(
                "Mohon isi nama dan nominal dengan benar! Nominal harus lebih dari 0."
            );
        }
    });

    document
        .getElementById("saveBudgetButton")
        .addEventListener("click", saveBudget);
});

// --- Fungsi renderCustomCategoriesList ---
function renderCustomCategoriesList() {
    const customListDiv = document.getElementById("customCategoriesList");
    const confirmBtn = document.getElementById("confirmCategories");
    customListDiv.innerHTML = "";

    if (customCategories.length === 0) {
        customListDiv.innerHTML =
            '<p class="no-categories">Belum ada kebutuhan lainnya ditambahkan.</p>';
        if (confirmBtn) confirmBtn.disabled = true;
        return;
    }

    if (confirmBtn) confirmBtn.disabled = false;

    const listHeader = document.createElement("h4");
    listHeader.textContent = "Kebutuhan yang Telah Ditambahkan:";
    customListDiv.appendChild(listHeader);

    customCategories.forEach((category) => {
        const item = document.createElement("div");
        item.className = "custom-category-item";
        item.innerHTML = `
            <span>${category.name}: Rp ${formatNumber(category.amount)}</span>
            <button data-id="${
                category.id
            }" class="delete-custom-btn">Hapus</button>
        `;
        customListDiv.appendChild(item);
    });

    document.querySelectorAll(".delete-custom-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const idToDelete = this.getAttribute("data-id");
            customCategories = customCategories.filter(
                (cat) => cat.id !== idToDelete
            );
            renderCustomCategoriesList();
            if (customCategories.length === 0) {
                document.getElementById("lainnya").checked = false;
                document.getElementById("customCategoryModal").style.display =
                    "none";
            }
        });
    });
}

// --- Fungsi calculateStandardAllocations ---
function calculateStandardAllocations(budgetForStandard, standardCategories) {
    const weights = {
        kos: 1.2,
        makan: 1.2,
        hiburan: 0.7,
        internet: 0.5,
        darurat: 0.6,
    };
    const totalWeight = standardCategories.reduce(
        (sum, cat) => sum + (weights[cat] || 1),
        0
    );
    const allocations = {};

    if (totalWeight > 0 && budgetForStandard > 0) {
        standardCategories.forEach((cat) => {
            allocations[cat] =
                (budgetForStandard * (weights[cat] || 1)) / totalWeight;
        });
    } else {
        standardCategories.forEach((cat) => {
            allocations[cat] = 0;
        });
    }
    return allocations;
}

// --- Fungsi displayResultsFromServer ---
function displayResultsFromServer(budgetPlan) {
    document.getElementById("tampilJumlahUangSaku").textContent = formatNumber(
        budgetPlan.total_pocket_money
    );
    document.querySelector(
        "#uang-saku-display .hasil-pesan"
    ).textContent = `Total uang saku bulanan Anda.`;

    const standardDisplayMap = {
        kos: {
            spanId: "tampilJumlahTempatTinggal",
            boxId: "uang-tempat-tinggal-display",
            title: "Tempat Tinggal",
            defaultMessage: "Biaya kos/akomodasi bulanan",
        },
        makan: {
            spanId: "tampilJumlahMakan",
            boxId: "uang-makan-display",
            title: "Budget Makan Sebulan",
            defaultMessage: "Biaya makan bulanan",
        },
        hiburan: {
            spanId: "tampilJumlahHiburan",
            boxId: "uang-hiburan-display",
            title: "Hiburan Perbulan",
            defaultMessage: "Alokasi hiburan bulanan",
        },
        internet: {
            spanId: "tampilJumlahInternet",
            boxId: "uang-internet-display",
            title: "Budget Internet",
            defaultMessage: "Biaya internet bulanan",
        },
        darurat: {
            spanId: "tampilJumlahDanaDarurat",
            boxId: "dana-darurat-display",
            title: "Dana Darurat Perbulan",
            defaultMessage: "Tabungan darurat bulanan",
        },
    };

    Object.values(standardDisplayMap).forEach((mapInfo) => {
        document.getElementById(mapInfo.spanId).textContent = "0";
        document.querySelector(`#${mapInfo.boxId} .hasil-pesan`).textContent =
            "Tidak dialokasikan.";
    });

    const existingCustomContainer = document.getElementById(
        "custom-categories-results-container"
    );
    if (existingCustomContainer) {
        existingCustomContainer.remove();
    }

    const customItemsToShow = [];

    if (budgetPlan.items && Array.isArray(budgetPlan.items)) {
        budgetPlan.items.forEach((item) => {
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
                    ).textContent = mapInfo.defaultMessage;
                }
            }
        });
    }

    if (customItemsToShow.length > 0) {
        const cekWrapper = document.getElementById("cekWrapper");
        const customContainer = document.createElement("div");
        customContainer.className = "hasil-container custom-results";
        customContainer.id = "custom-categories-results-container";

        const header = document.createElement("h2");
        header.className = "custom-header";
        header.textContent = "Kebutuhan Tambahan Lainnya";
        customContainer.appendChild(header);

        customItemsToShow.forEach((customItem) => {
            const categoryBox = document.createElement("div");
            categoryBox.className = "hasil-box custom-box";
            categoryBox.innerHTML = `
                <h3 class="hasil-title">${customItem.name}</h3>
                <div class="hasil-amount">
                    Rp <span>${formatNumber(customItem.amount)}</span>
                </div>
                <p class="hasil-pesan">Kebutuhan tambahan.</p>
            `;
            customContainer.appendChild(categoryBox);
        });
        if (cekWrapper) cekWrapper.appendChild(customContainer);
    }
}

// --- Fungsi resetDisplayResults ---
function resetDisplayResults() {
    document.getElementById("tampilJumlahUangSaku").textContent = "0";
    document.querySelector("#uang-saku-display .hasil-pesan").textContent =
        "Belum ada data.";

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
    Object.values(standardDisplayMap).forEach((mapInfo) => {
        if (document.getElementById(mapInfo.spanId))
            document.getElementById(mapInfo.spanId).textContent = "0";
        if (document.querySelector(`#${mapInfo.boxId} .hasil-pesan`))
            document.querySelector(
                `#${mapInfo.boxId} .hasil-pesan`
            ).textContent = "Belum ada data.";
    });
    const existingCustomContainer = document.getElementById(
        "custom-categories-results-container"
    );
    if (existingCustomContainer) {
        existingCustomContainer.remove();
    }
}

// --- Helper formatNumber ---
function formatNumber(num) {
    if (typeof num !== "number") {
        num = parseFloat(num) || 0;
    }
    return num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// --- Navigasi Mobile ---
const menuIcon = document.getElementById("menu-icon");
const menuList = document.getElementById("menu-list");

if (menuIcon && menuList) {
    menuIcon.addEventListener("click", () => {
        menuList.classList.toggle("hidden");
    });
}
