




window.acceptCookies = function acceptCookies() {
    // Logique pour enregistrer l'acceptation des cookies
    document.getElementById("cookiePopup").style.display = "none";
    localStorage.setItem("cookieConsent", "accepted");
}

window.openSettings = function openSettings() {
    // Logique pour ouvrir un menu de personnalisation (si besoin)
    if (parent.window.location.pathname !== '/login') { parent.window.location = "/logout" }
}



document.addEventListener('DOMContentLoaded', function () {

    if (!localStorage.getItem("cookieConsent")) {
        document.getElementById("cookiePopup").style.display = "flex";
    }

    if (localStorage.getItem("cookieConsent") && localStorage.getItem("cookieConsent") === "accepted") {
        document.getElementById("cookiePopup").style.display = "none";
    }
})