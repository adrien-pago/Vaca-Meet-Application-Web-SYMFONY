$(document).ready(function() {
    // Chargement des structures existantes
    $('#load-structures').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        var url = $(this).data('url'); // Récupère l'URL depuis l'attribut data-url

        // Charge le contenu depuis l'URL récupérée
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response); // Remplace le contenu dynamique avec le HTML chargé
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors du chargement des données :', error);
            }
        });
    });

    // Gestion du clic sur le bouton "Ajouter une structure"
    $('#add-structure').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        // Récupérer le nom du camping à partir de l'élément caché
        var nomCamping = $('#current-camping').text();
        console.log("Nom du camping récupéré:", nomCamping);

        if (!nomCamping) {
            alert("Le nom du camping n'a pas été trouvé.");
            return;
        }

        // Création de la nouvelle ligne HTML sans la colonne #
        var newRow = '<tr>' +
                        '<td>' + nomCamping + '</td>' + // Affichage direct du nom du camping
                        '<td><input type="text" class="form-control" name="libelleStructure" placeholder="Libellé"></td>' +
                        '<td><input type="number" class="form-control" name="nbStructure" placeholder="Nombre"></td>' +
                        '<td>' +
                            '<button class="btn btn-sm btn-success validate-structure">V</button>' +
                            '<button class="btn btn-sm btn-danger cancel-structure">X</button>' +
                        '</td>' +
                    '</tr>';

        // Insérer la nouvelle ligne au début du tableau
        $('#structure-table tbody').prepend(newRow);

        // Focus sur le premier champ de saisie (Libellé)
        $('#structure-table tbody tr:first-child input[name="libelleStructure"]').focus();
    });

    // Gestion du clic sur les boutons de validation et d'annulation
    $('#structure-table').on('click', '.validate-structure', function() {
        var $this = $(this); // Sauvegarder la référence du bouton de validation

        // Récupérer les valeurs saisies
        var libelle = $this.closest('tr').find('input[name="libelleStructure"]').val();
        var nombre = $this.closest('tr').find('input[name="nbStructure"]').val();

        // Envoyer les données au serveur via AJAX
        var url = '/structure/new'; // Adapter en fonction de votre route Symfony
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                libelleStructure: libelle,
                nbStructure: nombre
            },
            success: function(response) {
                // Mettre à jour la ligne dans le tableau avec les données de la réponse
                var $row = $this.closest('tr');
                $row.find('td:nth-child(2)').text(libelle); // Colonne Libellé
                $row.find('td:nth-child(3)').text(nombre); // Colonne Nombre

                // Modifier les boutons d'action
                $row.find('td:nth-child(4)').html(
                    '<a href="#" class="btn btn-sm btn-warning">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger">Supprimer</a>'
                );

                // Retirer les champs de saisie et remplacer par les valeurs
                $row.find('input').remove();
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de l\'ajout de la structure :', error);
                alert('Une erreur est survenue lors de l\'ajout de la structure.');
            }
        });
    });

    // Gestion du clic sur le bouton d'annulation
    $('#structure-table').on('click', '.cancel-structure', function() {
        // Supprimer la ligne en cas d'annulation
        $(this).closest('tr').remove();
    });
});