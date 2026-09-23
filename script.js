"use strict";


// Alle Produkte speichern
let produkteListe = [];
let selectedProductId = null;
// =========================
// Produkte aus PHP laden
// =========================

$.ajax({
    url: "get_products.php",
    method: "GET",
    dataType: "json",

    success: function (produkte) {

        // Produkte speichern
        produkteListe = produkte;

        // Container für die Produkte auswählen
        let container = $("#food-menu-container");

        // Alle Produkte durchlaufen
        produkte.forEach(function (produkt) {

            // HTML für ein Produkt erstellen
            let produktHTML = `
<div class="food-menu-box">

    <div class="food-menu-img">
        <img src="images/${produkt.bild}" alt="${produkt.name}" class="img-responsive img-curve">
    </div>

<div class="food-menu-desc">

    <h4>${produkt.name}</h4>

    <p class="food-price">
        ${produkt.preis} €
    </p>

    <p class="food-detail">
        ${produkt.beschreibung}
    </p>

    <br>

        <a href="#order"
           class="btn btn-primary order-btn"
           data-id="${produkt.id}">
            Order Now
        </a>

</div>

</div>
`;

            // Produkt in den Container einfügen
            container.append(produktHTML);
        });
    },

    error: function (xhr, status, error) {

        console.log("Status:", xhr.status);
        console.log("Fehler:", error);
        console.log("Antwort:", xhr.responseText);
    }
});


// =========================
// Order Now Button
// =========================

// Da die Buttons dynamisch mit JavaScript erstellt werden,
// behandeln wir den Klick über das Dokument.

$(document).on("click", ".order-btn", function (event) {

    // Verhindert das normale Verhalten des Links
    event.preventDefault();

    // ID des ausgewählten Produkts auslesen
    let produktId = $(this).data("id");


    // Produkt anhand der ID suchen
    let produkt = produkteListe.find(function (produkt) {
        return produkt.id == produktId;
    });

    // Prüfen, ob das Produkt gefunden wurde
    if (!produkt) {
        console.log("Produkt wurde nicht gefunden.");
        return;
    }
    // Produkt-ID speichern
    selectedProductId = produkt.id;
    // Kundendaten laden
    $.ajax({

        url: "get-customer.php",
        method: "GET",
        dataType: "json",

        success: function (kunde) {

            if (kunde.logged_in) {

                // E-Mail automatisch eintragen
                $("input[name='email']").val(kunde.email);

                // Name automatisch eintragen
                $("input[name='full-name']").val(kunde.username);
            }

        },

        error: function () {

            console.log("Kundendaten konnten nicht geladen werden.");

        }

    });

    // Produktdaten in das Order-Formular einfügen
    $("#order-image").attr("src", "images/" + produkt.bild);
    $("#order-image").attr("alt", produkt.name);

    $("#order-name").text(produkt.name);

    $("#order-price").text(produkt.preis + " €");


    // Food Menu ausblenden
    $("#foods").hide();

    // Order-Bereich anzeigen
    $("#order").show();


    // Zum Order-Bereich scrollen
    $("html, body").animate({
        scrollTop: $("#order").offset().top
    }, 1000);


    // Produkt-ID in der Konsole anzeigen
    console.log("Ausgewählte Produkt-ID:", produktId);
});



// =========================// Search mit AJAX // =========================

$("#search-form").on("submit", function (event) {

    // Verhindert das Neuladen der Seite
    event.preventDefault();

    // Suchbegriff aus dem Eingabefeld holen
    let suchbegriff = $("#search-input").val().toLowerCase().trim();


    // AJAX-Anfrage an PHP senden
    $.ajax({

        url: "search-products.php",
        method: "GET",
        data: {
            search: suchbegriff
        },
        dataType: "json",
        success: function (produkte) {

    // Food-Container auswählen
    let container = $("#food-menu-container");

    // Alte Produkte entfernen
    container.empty();


    // Prüfen, ob Produkte gefunden wurden
    if (produkte.length === 0) {

        container.html(`
        <p class="text-center">
        Keine Produkte gefunden.
</p>

    <br>

        <div class="text-center">
            <a href="#foods"
               id="show-all-foods"
               class="btn btn-primary">
                Alle Produkte anzeigen
            </a>
        </div>
        `);

        return;
        }


        // Gefundene Produkte anzeigen
        produkte.forEach(function (produkt) {

        let produktHTML = `
            <div class="food-menu-box">

                <div class="food-menu-img">

                    <img src="images/${produkt.bild}"
                         alt="${produkt.name}"
                         class="img-responsive img-curve">

                </div>

                <div class="food-menu-desc">

                    <h4>${produkt.name}</h4>

                    <p class="food-price">
                        ${produkt.preis} €
                    </p>

                    <p class="food-detail">
                        ${produkt.beschreibung}
                    </p>

                    <br>

                    <a href="#order"
                       class="btn btn-primary order-btn"
                       data-id="${produkt.id}">
                        Order Now
                    </a>

                </div>

            </div>
        `;

        // Produkt anzeigen
        container.append(produktHTML);
    });


        // Zum Food-Menü scrollen
        $("html, body").animate({
        scrollTop: $("#foods").offset().top
    }, 800);
        }



        });

});
// =========================
// Smooth Scroll für Navbar
// =========================

$('.navbar a[href^="#"]').on("click", function (event) {

    event.preventDefault();

    let ziel = $(this).attr("href");

    $("html, body").animate({
        scrollTop: $(ziel).offset().top
    }, 800);
});


// =========================
// Categories Slider
// =========================

// Kategorien speichern
let categories = [
    {
        name: "Pizza",
        image: "images/pizza.jpg"
    },
    {
        name: "Burger",
        image: "images/burger.jpg"
    },
    {
        name: "Döner",
        image: "images/doener.jpg"
    }
];


// Aktuelle Kategorie
let categoryIndex = 0;


// Kategorie anzeigen
function showCategory() {

    $("#category-image").fadeOut(700, function () {

        $(this).attr(
            "src",
            categories[categoryIndex].image
        );

        $(this).attr(
            "alt",
            categories[categoryIndex].name
        );

        $(this).fadeIn(900);
    });

    $("#category-name").fadeOut(600, function () {

        $(this).text(
            categories[categoryIndex].name
        );

        $(this).fadeIn(800);
    });
}


// Nächste Kategorie
function nextCategory() {

    categoryIndex++;

    if (categoryIndex >= categories.length) {
        categoryIndex = 0;
    }

    showCategory();
}


// Vorherige Kategorie
function previousCategory() {

    categoryIndex--;

    if (categoryIndex < 0) {
        categoryIndex = categories.length - 1;
    }

    showCategory();
}


// Button "Next"
$("#category-next").on("click", function (event) {

    event.preventDefault();

    nextCategory();
});


// Button "Previous"
$("#category-prev").on("click", function (event) {

    event.preventDefault();

    previousCategory();
});


// Erste Kategorie anzeigen
showCategory();


// Automatischer Wechsel alle 3 Sekunden
setInterval(function () {

    nextCategory();

}, 3000);

// =========================
// Calculate Total Price
// =========================

$("#order-quantity").on("input", function () {

    // Menge aus dem Eingabefeld holen
    let quantity = parseInt($(this).val());

    // Aktuellen Produktpreis holen
    let priceText = $("#order-price").text();

    // € entfernen und Preis in eine Zahl umwandeln
    let price = parseFloat(priceText.replace("€", "").trim());

    // Gesamtpreis berechnen
    let total = price * quantity;

    // Gesamtpreis anzeigen
    $("#order-total").text("Total: " + total.toFixed(2) + " €");
});

// =========================
// Confirm Order
// =========================

$("#order-form").on("submit", function (event) {

    // Verhindert das Neuladen der Seite
    event.preventDefault();


    // Prüfen, ob der Kunde angemeldet ist
    $.ajax({

        url: "check-login.php",
        method: "GET",
        dataType: "json",

        success: function (response) {

            // Kunde ist nicht angemeldet
            if (!response.logged_in) {
                alert("Bitte melden Sie sich zuerst an.");
                window.location.href = "login.php";
                return;
            }


            // =========================
            // Bestellung speichern
            // =========================

            // Daten aus dem Formular holen
            let fullName = $("input[name='full-name']").val();
            let contact = $("input[name='contact']").val();
            let email = $("input[name='email']").val();
            let address = $("textarea[name='address']").val();


            // Menge holen
            let quantity = parseInt(
                $("#order-quantity").val()
            );


            // Produktname holen
            let productName = $("#order-name").text();


            // Preis holen
            let price = parseFloat(
                $("#order-price")
                    .text()
                    .replace("€", "")
                    .trim()
            );


            // Gesamtpreis berechnen
            let total = price * quantity;


            // FormData erstellen
            let formData = new FormData();

            formData.append(
                "produktId",
                selectedProductId
            );

            formData.append(
                "productName",
                productName
            );

            formData.append(
                "price",
                price
            );

            formData.append(
                "quantity",
                quantity
            );

            formData.append(
                "total",
                total
            );

            formData.append(
                "fullName",
                fullName
            );

            formData.append(
                "contact",
                contact
            );

            formData.append(
                "email",
                email
            );

            formData.append(
                "address",
                address
            );


            // Bestellung mit XMLHttpRequest senden
            let xhr = new XMLHttpRequest();

            xhr.open(
                "POST",
                "save_order.php",
                true
            );


            xhr.onload = function () {

                if (xhr.status === 200) {

                    // Erfolgsmeldung anzeigen
                    $("#order-message").text(
                        xhr.responseText
                    );


                    // Formular zurücksetzen
                    $("#order-form")[0].reset();


                    // Menge wieder auf 1 setzen
                    $("#order-quantity").val(1);


                    // Nach 2 Sekunden zurück zum Food-Bereich
                    setTimeout(function () {

                        $("#order").hide();

                        $("#foods").show();


                        $("html, body").animate({
                            scrollTop:
                            $("#foods").offset().top
                        }, 800);


                        // Erfolgsmeldung entfernen
                        $("#order-message").text("");

                    }, 2000);


                } else {

                    $("#order-message").text(
                        "Fehler beim Senden der Bestellung."
                    );
                }
            };


            xhr.send(formData);

        },


        error: function () {

            $("#order-message").text(
                "Fehler bei der Login-Prüfung."
            );
        }

    });

});

// =========================
// Kunden-Login im Navbar
// =========================

$.ajax({

    url: "check-login.php",
    method: "GET",
    dataType: "json",

    success: function (response) {

        if (response.logged_in) {

            $("#customer-menu").html(`
            <span>
                Willkommen, ${response.username} 👋
            </span>

            <a href="customer-logout.php">
                Abmelden
            </a>
        `);
        }
    },

    error: function () {

        console.log("Login-Status konnte nicht geprüft werden.");

    }

});


// ==================== CONTACT FORM ====================

$(document).ready(function () {

    console.log("Contact JavaScript geladen.");

    $("#contact-form").on("submit", function (event) {

        event.preventDefault();

        console.log("Contact Form wurde abgeschickt.");


        // Daten aus dem Formular holen
        let name = $("#contact-name").val().trim();
        let email = $("#contact-email").val().trim();
        let subject = $("#contact-subject").val().trim();
        let message = $("#contact-message").val().trim();

        let result = $("#contact-message-result");


        // ====================
        // VALIDATION
        // ====================

        if (name === "" ||
            email === "" ||
            subject === "" ||
            message === "") {

            result
                .text("Bitte füllen Sie alle Felder aus.")
                .css("color", "red");

            return;
        }


        // E-Mail prüfen
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {

            result
                .text("Bitte geben Sie eine gültige E-Mail-Adresse ein.")
                .css("color", "red");

            return;
        }


        // ====================
        // AJAX
        // ====================

        $.ajax({

            url: "save_contact.php",

            method: "POST",

            data: {
                name: name,
                email: email,
                subject: subject,
                message: message
            },

            dataType: "json",

            success: function (response) {

                console.log("PHP Response:");
                console.log(response);

                if (response.success) {

                    result
                        .text(response.message)
                        .css("color", "green");

                    $("#contact-form")[0].reset();

                } else {

                    result
                        .text(response.message)
                        .css("color", "red");
                }
            },

            error: function (xhr, status, error) {

                console.log("AJAX Error:", error);
                console.log("HTTP Status:", xhr.status);
                console.log("Server Response:", xhr.responseText);

                result
                    .text("Fehler beim Senden der Nachricht.")
                    .css("color", "red");
            }

        });

    });

});
