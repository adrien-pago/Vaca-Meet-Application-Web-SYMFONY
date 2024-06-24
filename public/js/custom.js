// public/js/custom.js
$(document).ready(function() {
    // Exemple de chargement de contenu dynamique (à adapter selon vos besoins)
    $('ul.list-group a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#dynamic-content').load(url);
    });

    // Ajoutez d'autres scripts JavaScript selon vos besoins
});
