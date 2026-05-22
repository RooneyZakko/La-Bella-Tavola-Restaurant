document.addEventListener("DOMContentLoaded", function () {
    const reservationForm = document.getElementById("reservationForm");
    const contactForm = document.getElementById("contactForm");

    if (reservationForm) {
        reservationForm.addEventListener("submit", function (e) {
            const guests = document.getElementById("guests").value;
            const email = document.getElementById("email").value;

            if (guests < 1) {
                alert("Aantal personen moet minimaal 1 zijn.");
                e.preventDefault();
            }

            if (!email.includes("@")) {
                alert("Vul een geldig e-mailadres in.");
                e.preventDefault();
            }
        });
    }

    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            const email = document.getElementById("contact_email").value;
            const message = document.getElementById("contact_message").value.trim();

            if (!email.includes("@")) {
                alert("Vul een geldig e-mailadres in.");
                e.preventDefault();
            }

            if (message.length < 5) {
                alert("Je bericht is te kort.");
                e.preventDefault();
            }
        });
    }
});