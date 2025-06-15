document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("umr-table-body");
    const addForm = document.getElementById("addForm");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const API_ENDPOINT = "/admin/api/budget/umr";

    const postData = async (url, method, body) => {
        const response = await fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(body),
        });
        if (!response.ok) {
            const err = await response.json();
            throw new Error(err.message || "Terjadi kesalahan.");
        }
        return await response.json();
    };

    const renderTable = async () => {
        try {
            const response = await fetch(API_ENDPOINT);
            const data = await response.json();
            tableBody.innerHTML = "";
            if (data.length === 0) {
                tableBody.innerHTML =
                    '<tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>';
                return;
            }
            data.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                            <td>${item.id}</td>
                            <td><input type="text" class="form-control form-control-sm" value="${
                                item.nama_kota
                            }" data-field="nama_kota"></td>
                            <td><input type="text" class="form-control form-control-sm" value="${
                                item.provinsi || ""
                            }" data-field="provinsi"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${
                                item.umr
                            }" data-field="umr"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${
                                item.harga_makan_avg || ""
                            }" data-field="harga_makan_avg"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${
                                item.harga_kos_avg || ""
                            }" data-field="harga_kos_avg"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${
                                item.harga_transport_avg || ""
                            }" data-field="harga_transport_avg"></td>
                            <td>
                                <button class="btn btn-sm btn-success btn-update" data-id="${
                                    item.id
                                }" title="Simpan"><i class="bi bi-check-lg"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${
                                    item.id
                                }" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Gagal memuat data: ${error.message}</td></tr>`;
        }
    };

    tableBody.addEventListener("click", async (e) => {
        const button = e.target.closest("button");
        if (!button) return;
        const id = button.dataset.id;

        if (button.classList.contains("btn-update")) {
            const row = button.closest("tr");
            const inputs = row.querySelectorAll("input");
            let body = { id };
            inputs.forEach(
                (input) => (body[input.dataset.field] = input.value)
            );
            try {
                await postData(`${API_ENDPOINT}/${id}`, "PUT", body);
                alert("Data kota berhasil diperbarui!");
            } catch (e) {
                alert(`Error: ${e.message}`);
            }
        }

        if (button.classList.contains("btn-delete")) {
            if (confirm("Apakah Anda yakin?")) {
                try {
                    await postData(`${API_ENDPOINT}/${id}`, "DELETE", {});
                    alert("Data kota berhasil dihapus!");
                    renderTable();
                } catch (e) {
                    alert(`Error: ${e.message}`);
                }
            }
        }
    });

    addForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(addForm);
        const body = Object.fromEntries(formData.entries());
        try {
            await postData(API_ENDPOINT, "POST", body);
            alert("Kota baru berhasil ditambahkan!");
            addForm.reset();
            renderTable();
        } catch (e) {
            alert(`Error: ${e.message}`);
        }
    });

    renderTable();
});
