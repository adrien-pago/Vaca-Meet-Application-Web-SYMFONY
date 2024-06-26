$(document).ready(function() {
    ///////////////// Chargement des structures existantes/////////////////
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


    ///////////////// Ajout d'une nouvelle structure /////////////////
    $('#add-structure').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        var newRow = '<tr>' +
                        '<td class="structure-id" style="display: none;"></td>' + // Colonne pour l'ID (cachée)
                        '<td><input type="text" class="form-control" name="libelleStructure" placeholder="Libellé"></td>' +
                        '<td><input type="number" class="form-control" name="nbStructure" placeholder="Nombre"></td>' +
                        '<td>' +
                            '<button class="btn btn-sm btn-success validate-structure">V</button>' +
                            '<button class="btn btn-sm btn-danger cancel-structure">X</button>' +
                        '</td>' +
                    '</tr>';

        $('#structure-table tbody').prepend(newRow);
        $('#structure-table tbody tr:first-child input[name="libelleStructure"]').focus();
    });

    // Validation de l'ajout d'une structure
    $('#structure-table').on('click', '.validate-structure', function() {
        var $this = $(this);
        var $row = $this.closest('tr');

        var libelle = $row.find('input[name="libelleStructure"]').val();
        var nombre = $row.find('input[name="nbStructure"]').val();

        var url = '/structure/new';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                libelleStructure: libelle,
                nbStructure: nombre
            },
            success: function(response) {
                $row.find('.structure-id').text(response.idStructure); // Met à jour l'ID de la structure (caché)

                $row.find('td:nth-child(2)').text(libelle); // Met à jour le libellé
                $row.find('td:nth-child(3)').text(nombre); // Met à jour le nombre

                $row.find('td:nth-child(4)').html(
                    '<a href="#" class="btn btn-sm btn-warning edit-structure">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger delete-structure">Supprimer</a>'
                );

                $row.find('input').remove(); // Supprime les champs d'entrée

                $row.removeClass('new-row');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de l\'ajout de la structure :', error);
                alert('Une erreur est survenue lors de l\'ajout de la structure.');
            }
        });
    });

    // Annuler l'ajout d'une nouvelle structure
    $('#structure-table').on('click', '.cancel-structure', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    ///////////////// Modification d'une structure existante /////////////////
    $('#structure-table').on('click', '.edit-structure', function(event) {
        event.preventDefault();

        var $button = $(this);
        var $row = $button.closest('tr');

        var structureId = $row.find('.structure-id').text().trim(); // Récupère l'ID de la structure

        var originalLibelle = $row.find('td:nth-child(2)').text().trim();
        var originalNombre = $row.find('td:nth-child(3)').text().trim();

        $row.find('td:nth-child(2)').html('<input type="text" class="form-control" name="edit-libelle" value="' + originalLibelle + '">');
        $row.find('td:nth-child(3)').html('<input type="number" class="form-control" name="edit-nombre" value="' + originalNombre + '">');

        // Mettre les boutons V et X dans la dernière colonne
        $row.find('td:nth-child(4)').html(
            '<button class="btn btn-sm btn-success validate-edit">V</button>' +
            '<button class="btn btn-sm btn-danger cancel-edit">X</button>'
        );

        $row.addClass('editing');
    });

    // Validation de la modification d'une structure
    $('#structure-table').on('click', '.validate-edit', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var structureId = $row.find('.structure-id').text().trim(); // Récupère l'ID de la structure depuis la colonne cachée

        var editUrl = '/structure/' + structureId + '/edit'; // URL pour la modification avec l'ID correct
        var newLibelle = $row.find('input[name="edit-libelle"]').val();
        var newNombre = $row.find('input[name="edit-nombre"]').val();

        $.ajax({
            url: editUrl,
            type: 'POST',
            data: {
                libelleStructure: newLibelle,
                nbStructure: newNombre
            },
            success: function(response) {
                $row.find('td:nth-child(2)').text(newLibelle); // Met à jour le libellé
                $row.find('td:nth-child(3)').text(newNombre); // Met à jour le nombre

                $row.find('td:nth-child(4)').html(
                    '<a href="#" class="btn btn-sm btn-warning edit-structure">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger delete-structure">Supprimer</a>'
                );

                $row.removeClass('editing');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la modification de la structure :', error);
                alert('Une erreur est survenue lors de la modification de la structure.');
            }
        });
    });

     // Annuler la modification d'une structure
     $('#structure-table').on('click', '.cancel-edit', function() {
        // Recharger les données pour annuler les modifications et restaurer l'état original
        $('#load-structures').trigger('click'); // Déclenche le chargement des données
    });

    
    ///////////////// Suppression d'une structure /////////////////
    $('#structure-table').on('click', '.delete-structure', function(event) {
        event.preventDefault();

        var $row = $(this).closest('tr');
        var structureId = $row.find('.structure-id').text().trim(); // Récupère l'ID de la structure

        var deleteUrl = '/structure/' + structureId + '/delete'; // URL pour la suppression avec l'ID correct

        if (confirm('Êtes-vous sûr de vouloir supprimer cette structure ?')) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    $row.remove();
                    alert('Structure supprimée avec succès!');
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression de la structure :', error);
                    alert('Une erreur est survenue lors de la suppression de la structure.');
                }
            });
        }
    });
});
