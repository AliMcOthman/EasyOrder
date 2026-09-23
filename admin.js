// =========================
// ADMIN.JS
// =========================

"use strict";

// Alle Bestellungen speichern
let alleBestellungen = [];


// =========================
// Bestellungen laden
// =========================

function ladeBestellungen() {

    let xhr = new XMLHttpRequest();

    xhr.open("GET", "get_orders.php", true);

    xhr.onload = function () {

        if (xhr.status === 200) {

            let bestellungen = JSON.parse(xhr.responseText);

            // Alle Bestellungen speichern
            alleBestellungen = bestellungen;


            // =========================
            // Statistiken
            // =========================

            let totalOrders = bestellungen.length;

            let totalSales = bestellungen.reduce(function (summe, bestellung) {

                return summe + parseFloat(bestellung.gesamtpreis);

            }, 0);


            $("#total-orders").text(totalOrders);

            $("#total-sales").text(
                totalSales.toFixed(2) + " €"
            );


            // =========================
            // Bestellungen anzeigen
            // =========================

            zeigeBestellungen(bestellungen);

        } else {

            console.log(
                "Fehler beim Laden der Bestellungen."
            );
        }
    };


    xhr.onerror = function () {

        console.log(
            "Fehler bei der Verbindung zum Server."
        );

        $("#refresh-orders").removeClass("loading");
    };


    xhr.send();
}


// =========================
// Bestellungen anzeigen
// =========================

function zeigeBestellungen(bestellungen) {

    let container = $("#orders-container");


    // Keine Bestellungen
    if (bestellungen.length === 0) {

        container.html(`
            <p>Keine Bestellungen vorhanden.</p>
        `);

        return;
    }


    // Tabelle erstellen
    let table = `
        <table class="admin-orders">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Produkt</th>

                    <th>Preis</th>

                    <th>Menge</th>

                    <th>Gesamt</th>

                    <th>Name</th>

                    <th>Telefon</th>

                    <th>E-Mail</th>

                    <th>Adresse</th>

                    <th>Datum</th>

                    <th>Status</th>

                    <th>Aktion</th>

                </tr>

            </thead>

            <tbody>
    `;


    // Alle Bestellungen durchlaufen
    bestellungen.forEach(function (bestellung) {

        table += `

            <tr>

                <td>
                    ${bestellung.id}
                </td>

                <td>
                    ${bestellung.produkt_name}
                </td>

                <td>
                    ${bestellung.preis} €
                </td>

                <td>
                    ${bestellung.menge}
                </td>

                <td>
                    ${bestellung.gesamtpreis} €
                </td>

                <td>
                    ${bestellung.full_name}
                </td>

                <td>
                    ${bestellung.contact}
                </td>

                <td>
                    ${bestellung.email}
                </td>

                <td>
                    ${bestellung.address}
                </td>

                <td>
                    ${bestellung.bestelldatum}
                </td>


                <!-- Status -->

                <td>

                    <select
                        class="order-status"
                        data-id="${bestellung.id}">

                        <option
                            value="Neu"
                            ${bestellung.status === "Neu" ? "selected" : ""}>
                            Neu
                        </option>

                        <option
                            value="In Bearbeitung"
                            ${bestellung.status === "In Bearbeitung" ? "selected" : ""}>
                            In Bearbeitung
                        </option>

                        <option
                            value="Erledigt"
                            ${bestellung.status === "Erledigt" ? "selected" : ""}>
                            Erledigt
                        </option>

                    </select>

                </td>


                <!-- Löschen -->

                <td>

                    <button
                        class="delete-order"
                        data-id="${bestellung.id}">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </td>

            </tr>

        `;
    });


    table += `

            </tbody>

        </table>

    `;


    // Tabelle anzeigen
    container.html(table);
}


// =========================
// Bestellungen laden
// =========================

ladeBestellungen();


// =========================
// Refresh Button
// =========================

$("#refresh-orders").on("click", function () {

    let button = $(this);

    button.addClass("loading");

    ladeBestellungen();


    setTimeout(function () {

        button.removeClass("loading");

    }, 2000);

});


// =========================
// STATUS FILTER
// =========================

$("#status-filter").on("change", function () {

    let ausgewählterStatus = $(this).val();

    let gefilterteBestellungen = alleBestellungen;


    // Nur bestimmten Status anzeigen
    if (ausgewählterStatus !== "Alle") {

        gefilterteBestellungen = alleBestellungen.filter(
            function (bestellung) {

                return bestellung.status === ausgewählterStatus;

            }
        );
    }


    // Gefilterte Bestellungen anzeigen
    zeigeBestellungen(gefilterteBestellungen);

});


// =========================
// STATUS ÄNDERN
// =========================

$(document).on("change", ".order-status", function () {

    let orderId = $(this).data("id");

    let status = $(this).val();


    // Alte Status-Klassen entfernen
    $(this).removeClass(
        "status-neu status-in-bearbeitung status-erledigt"
    );


    // Neue Status-Klasse hinzufügen
    $(this).addClass(
        "status-" +
        status.replaceAll(" ", "-").toLowerCase()
    );


    // Status in Datenbank speichern
    $.ajax({

        url: "update_order_status.php",

        method: "POST",

        data: {

            orderId: orderId,

            status: status
        },


        success: function (antwort) {

            console.log(antwort);

        },


        error: function () {

            console.log(
                "Fehler beim Aktualisieren des Status."
            );

        }

    });

});


// =========================
// BESTELLUNG LÖSCHEN
// =========================

$(document).on("click", ".delete-order", function () {

    // Sicherheitsabfrage
    if (!confirm(
        "Möchten Sie diese Bestellung wirklich löschen?"
    )) {

        return;
    }


    let orderId = $(this).data("id");


    $.ajax({

        url: "delete_order.php",

        method: "POST",

        data: {

            orderId: orderId

        },


        success: function (antwort) {

            console.log(antwort);


            // Tabelle neu laden
            ladeBestellungen();

        },


        error: function () {

            console.log(
                "Fehler beim Löschen der Bestellung."
            );

        }

    });

});

// =========================
// CONTACT MESSAGE COUNT
// =========================

function ladeContactCount() {

    $.ajax({

        url: "get_contact_count.php",

        method: "GET",

        dataType: "json",

        success: function (response) {

            $("#contact-count").text(response.count);

        },

        error: function () {

            console.log(
                "Fehler beim Laden der Contact-Nachrichten."
            );

        }

    });

}


// Contact-Anzahl laden
ladeContactCount();


// Alle 10 Sekunden aktualisieren
setInterval(function () {

    ladeContactCount();

}, 10000);

// =========================
// CONTACT NACHRICHT LÖSCHEN
// =========================

$(document).on("click", ".delete-contact", function () {

    // ID der Nachricht
    let messageId = $(this).data("id");


    // Sicherheitsabfrage
    if (!confirm(
        "Möchten Sie diese Nachricht wirklich löschen?"
    )) {

        return;
    }


    // AJAX Anfrage
    $.ajax({

        url: "delete_contact.php",

        method: "POST",

        data: {
            id: messageId
        },

        dataType: "json",

        success: function (response) {

            console.log("Delete Response:", response);

            if (response.success) {

                location.reload();

            } else {

                alert(response.message);
            }
        },

        error: function (xhr, status, error) {

            console.log("AJAX Fehler:", error);
            console.log("Server Response:", xhr.responseText);

            alert(
                "Fehler beim Löschen der Nachricht."
            );
        }

    });

});