jQuery(document).ready(function($) {
    $("#pnw_date").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            // Tutaj dodamy AJAX call do sprawdzenia dostępnych godzin
            $.ajax({
                url: pnw_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_available_times',
                    date: dateStr,
                    therapist: $("#pnw_therapist").val()
                },
                success: function(response) {
                    // Zaktualizuj pole wyboru godziny na podstawie odpowiedzi
                    $("#pnw_time").html(response);
                }
            });
        }
    });
});
