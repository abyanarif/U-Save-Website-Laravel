import { auth } from "./firebase-init.js";

export async function getFirebaseToken() {
    const user = auth.currentUser;
    if (user) {
        return await user.getIdToken();
    } else {
        console.warn("User belum login.");
        return null;
    }
}
