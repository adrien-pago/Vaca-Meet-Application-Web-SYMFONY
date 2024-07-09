$(document).ready(function() {
    // Exemple d'utilisation d'AJAX pour charger le contenu dynamique
    $('#dynamic-content').load('/planning');

    // Code pour gérer l'ajout de nouvelles activités au planning via formulaire
    $('#planning-form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '/planning',
            data: formData,
            success: function(response) {
                $('#dynamic-content').html(response);
            }
        });
    });
});