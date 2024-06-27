$(document).ready(function() {
    ///////////////// Chargement des activitées existantes/////////////////
    $('#load-activity').click(function(event) {
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


    ///////////////// Ajout d'une nouvelle activité /////////////////
    $('#add-activity').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        var newRow = '<tr>' +
                        '<td class="activity-id" style="display: none;"></td>' + // Colonne pour l'ID (cachée)
                        '<td><input type="text" class="form-control" name="libelleActivity" placeholder="Libellé"></td>' +
                        '<td>' +
                            '<button class="btn btn-sm btn-success validate-activity">V</button>' +
                            '<button class="btn btn-sm btn-danger cancel-activity">X</button>' +
                        '</td>' +
                    '</tr>';

        $('#activity-table tbody').prepend(newRow);
        $('#activity-table tbody tr:first-child input[name="libelleActivity"]').focus();
    });

    // Validation de l'ajout d'une activité
    $('#activity-table').on('click', '.validate-activity', function() {
        var $this = $(this);
        var $row = $this.closest('tr');

        var libelle = $row.find('input[name="libelleActivity"]').val();

        var url = '/activity/new';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                libelleActivity: libelle,
            },
            success: function(response) {
                $row.find('.activity-id').text(response.idActivity); // Met à jour l'ID de la activité (caché)
                $row.find('td:nth-child(2)').text(libelle); // Met à jour le libellé
                $row.find('td:nth-child(3)').html(
                    '<a href="#" class="btn btn-sm btn-warning edit-activity">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger delete-activity">Supprimer</a>'
                );

                $row.find('input').remove(); // Supprime les champs d'entrée

                $row.removeClass('new-row');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de l\'ajout de la activité :', error);
                alert('Une erreur est survenue lors de l\'ajout de la activité.');
            }
        });
    });

    // Annuler l'ajout d'une nouvelle activité
    $('#activity-table').on('click', '.cancel-activity', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    ///////////////// Modification d'une activité existante /////////////////
    $('#activity-table').on('click', '.edit-activity', function(event) {
        event.preventDefault();

        var $button = $(this);
        var $row = $button.closest('tr');

        var ActivityId = $row.find('.activity-id').text().trim(); // Récupère l'ID de la activité
        var originalLibelle = $row.find('td:nth-child(2)').text().trim();
      
        $row.find('td:nth-child(2)').html('<input type="text" class="form-control" name="edit-libelle" value="' + originalLibelle + '">');
     
        // Mettre les boutons V et X dans la dernière colonne
        $row.find('td:nth-child(3)').html(
            '<button class="btn btn-sm btn-success validate-edit">V</button>' +
            '<button class="btn btn-sm btn-danger cancel-edit">X</button>'
        );

        $row.addClass('editing');
    });

    // Validation de la modification d'une activité
    $('#activity-table').on('click', '.validate-edit', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var activityId = $row.find('.activity-id').text().trim(); // Récupère l'ID de la activité depuis la colonne cachée

        var editUrl = '/activity/' + activityId + '/edit'; // URL pour la modification avec l'ID correct
        var newLibelle = $row.find('input[name="edit-libelle"]').val();
     

        $.ajax({
            url: editUrl,
            type: 'POST',
            data: {
                libelleActivity: newLibelle, 
            },
            success: function(response) {
                $row.find('td:nth-child(2)').text(newLibelle); // Met à jour le libellé
                $row.find('td:nth-child(3)').html(
                    '<a href="#" class="btn btn-sm btn-warning edit-activity">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger delete-activity">Supprimer</a>'
                );

                $row.removeClass('editing');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la modification de la activité :', error);
                alert('Une erreur est survenue lors de la modification de la activité.');
            }
        });
    });

     // Annuler la modification d'une activité
     $('#activity-table').on('click', '.cancel-edit', function() {
        // Recharger les données pour annuler les modifications et restaurer l'état original
        $('#load-activity').trigger('click'); // Déclenche le chargement des données
    });

    
    ///////////////// Suppression d'une activité /////////////////
    $('#activity-table').on('click', '.delete-activity', function(event) {
        event.preventDefault();

        var $row = $(this).closest('tr');
        var activityId = $row.find('.activity-id').text().trim(); // Récupère l'ID de la structure

        var deleteUrl = '/activity/' + activityId + '/delete'; // URL pour la suppression avec l'ID correct

        if (confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    $row.remove();
                    alert('activité supprimée avec succès!');
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression de la activité :', error);
                    alert('Une erreur est survenue lors de la suppression de la activité.');
                }
            });
        }
    });
});
