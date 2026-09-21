"use strict";

console.log("script.js funktioniert!");

// Alle Produkte speichern
let produkteListe = [];


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
// ========================= // Smooth Scroll für Navbar // =========================
$('a[href^="#"]').on("click", function (event) {
    // Normales Springen verhindern
    event.preventDefault();

    // Ziel des Links auswählen
    let ziel = $(this).attr("href");

    // Smooth Scroll zum Ziel
    $("html, body").animate({ scrollTop: $(ziel).offset().top }, 800);
});

