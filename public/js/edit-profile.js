import { app, auth } from "./firebase-init.js";
import { onAuthStateChanged, updatePassword, EmailAuthProvider, reauthenticateWithCredential } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// --- Helper Functions ---
async function getFirebaseToken() {
    if (!auth.currentUser) return null;
    try {
        return await auth.currentUser.getIdToken(true);
    } catch (error) {
        console.error("Error getting Firebase token:", error);
        return null;
    }
}

// --- Main Logic ---
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-profile-form');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const statusMessage = document.getElementById('statusMessage');
    
    const usernameInput = document.getElementById('username');
    const universitySelect = document.getElementById('university');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const currentPasswordInput = document.getElementById('current-password');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    let currentUserData = {};

    // FIX: Implementasi fungsi untuk memuat daftar universitas
    async function loadUniversities() {
        try {
            const response = await fetch('/get-universities');
            if (!response.ok) throw new Error('Gagal memuat data universitas.');
            const universities = await response.json();
            
            universitySelect.innerHTML = '<option value="">-- Pilih Universitas --</option>';
            universities.forEach(univ => {
                const option = new Option(univ.name, univ.id);
                universitySelect.appendChild(option);
            });
        } catch (error) {
            console.error(error);
            universitySelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
    }

    // FIX: Implementasi fungsi untuk memuat data profil pengguna
    async function loadUserProfile(uid) {
        try {
            const response = await fetch(`/api/user-profile?firebase_uid=${uid}`);
            if (!response.ok) throw new Error('Gagal memuat profil pengguna.');
            
            currentUserData = await response.json();
            
            // Isi form dengan data yang ada
            usernameInput.value = currentUserData.username || '';
            emailInput.value = currentUserData.email || '';
            phoneInput.value = currentUserData.phone || '';
            
            // Atur pilihan universitas yang sesuai
            if (currentUserData.university_id) {
                universitySelect.value = currentUserData.university_id;
            }
        } catch (error) {
            console.error(error);
            statusMessage.textContent = 'Gagal memuat profil Anda.';
            statusMessage.style.color = 'red';
        }
    }

    // Cek status login dan jalankan fungsi pemuatan data
    onAuthStateChanged(auth, async (user) => {
        if (user) {
            // Jalankan kedua fungsi secara bersamaan
            await Promise.all([ 
                loadUniversities(), 
                loadUserProfile(user.uid) 
            ]);
            // Tampilkan form setelah semua data dimuat
            loadingIndicator.style.display = 'none';
            form.style.display = 'block';
        } else {
            window.location.href = '/sign-in';
        }
    });

    // Handle form submission (Logika ini tetap sama)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        statusMessage.textContent = 'Menyimpan...';
        statusMessage.style.color = 'black';

        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const currentPassword = currentPasswordInput.value;

        if (newPassword && newPassword !== confirmPassword) {
            statusMessage.textContent = 'Password baru dan konfirmasi tidak cocok!';
            statusMessage.style.color = 'red';
            return;
        }
        if (newPassword && !currentPassword) {
            statusMessage.textContent = 'Silakan masukkan password Anda saat ini untuk mengubahnya.';
            statusMessage.style.color = 'red';
            return;
        }

        const token = await getFirebaseToken();
        if (!token) {
            statusMessage.textContent = 'Sesi tidak valid. Silakan login kembali.';
            statusMessage.style.color = 'red';
            return;
        }

        const payload = {
            username: usernameInput.value,
            university_id: universitySelect.value,
            phone: phoneInput.value,
        };

        try {
            if (newPassword) {
                const user = auth.currentUser;
                const credential = EmailAuthProvider.credential(user.email, currentPassword);
                await reauthenticateWithCredential(user, credential);
                await updatePassword(user, newPassword);
                console.log("Password di Firebase berhasil diupdate.");
            }

            const backendResponse = await fetch('/api/user-profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            if (!backendResponse.ok) {
                const err = await backendResponse.json();
                throw new Error(err.message || 'Gagal memperbarui profil di server.');
            }
            
            statusMessage.textContent = 'Profil berhasil diperbarui!';
            statusMessage.style.color = 'green';

            setTimeout(() => { window.location.href = '/profile'; }, 1500);

        } catch (error) {
            console.error("Update error:", error);
            if (error.code === 'auth/wrong-password') {
                statusMessage.textContent = 'Error: Password saat ini yang Anda masukkan salah.';
            } else {
                statusMessage.textContent = `Error: ${error.message}`;
            }
            statusMessage.style.color = 'red';
        }
    });
});
