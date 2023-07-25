document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch("login.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.text())
        .then((data) => {
            if (data === "success") {
                // Dacă autentificarea a fost cu succes, facem redirect către pagina adaugaStudii.php
                window.location.href = "adaugaStudii.php";
            } else {
                // Altfel, afișăm mesajul de eroare primit
                document.getElementById("login-message").innerText = data;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
});
