document.addEventListener("DOMContentLoaded", function() {
    // Obsługa zakładek
    const tabs = document.querySelectorAll(".nav-tab");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", function(e) {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove("nav-tab-active"));
            tabContents.forEach(tc => tc.classList.remove("active"));
            this.classList.add("nav-tab-active");
            document.getElementById(this.getAttribute("data-tab")).classList.add("active");
        });
    });

    // Dodawanie specjalnych dat
    document.getElementById("add_special_date").addEventListener("click", function() {
        var div = document.createElement("div");
        div.innerHTML = '<input type="date" name="special_dates[]"><button type="button" class="remove_special_date">Usuń</button>';
        document.getElementById("special_dates").appendChild(div);
    });

    // Usuwanie specjalnych dat
    document.getElementById("special_dates").addEventListener("click", function(e) {
        if (e.target.classList.contains("remove_special_date")) {
            e.target.parentElement.remove();
        }
    });

    // Ukrywanie i pokazywanie pól czasu w zależności od checkboxa
    var dayCheckboxes = document.querySelectorAll(".day-off-checkbox");
    dayCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            var timeRange = this.closest("td").querySelectorAll("input[type=time]");
            if (this.checked) {
                timeRange.forEach(input => input.style.display = "none");
            } else {
                timeRange.forEach(input => input.style.display = "inline-block");
            }
        });
    });

    // Obsługa zapisu formularza za pomocą AJAX
    document.getElementById("submit").addEventListener("click", function() {
        var formData = new FormData(document.getElementById("przystanek-zespol-form"));
        fetch(ajaxurl + "?action=save_team_member", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.data.message);
            } else {
                alert(data.data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Wystąpił błąd podczas zapisywania danych.");
        });
    });
});
