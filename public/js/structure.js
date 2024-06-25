$(document).ready(function() {
    ///////////////////////// Chargement des structures existantes ///////////////////////////
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

   ////////////////////////// Gestion du clic sur le bouton "Ajouter une structure" /////////////////////////////////////////
    $('#add-structure').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        // Récupérer le nom du camping à partir de l'élément caché
        var nomCamping = $('#current-camping').text().trim();
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

    // Gestion du clic sur le bouton "Valider" pour l'ajout d'une structure
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
                    '<a href="#" class="btn btn-sm btn-danger delete-structure">Supprimer</a>'
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

    // Gestion du clic sur le bouton "Annuler" lors de l'ajout d'une structure
    $('#structure-table').on('click', '.cancel-structure', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        // Supprimer la ligne ajoutée
        $(this).closest('tr').remove();
    });

    //////////////////////////////////////gestion du clic sur le bouton "Modifier"///////////////////////////////////////////
    $('#structure-table').on('click', '.btn-warning', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        var $button = $(this);
        var editUrl = $button.attr('href'); // URL de modification récupérée depuis le lien
        var $row = $button.closest('tr'); // Ligne du tableau à modifier

        // Récupérer les valeurs actuelles
        var originalLibelle = $row.find('td:nth-child(3)').text().trim(); // Libellé actuel
        var originalNombre = $row.find('td:nth-child(4)').text().trim(); // Nombre actuel

        // Afficher les champs de saisie pour la modification avec les valeurs actuelles
        $row.find('td:nth-child(3)').html('<input type="text" class="form-control" name="edit-libelle" value="' + originalLibelle + '">');
        $row.find('td:nth-child(4)').html('<input type="number" class="form-control" name="edit-nombre" value="' + originalNombre + '">');

        // Remplacer les boutons "Modifier" et "Supprimer" par les boutons "Valider" et "Annuler"
        $row.find('td:nth-child(5)').html(
            '<button class="btn btn-sm btn-success validate-edit">V</button>' +
            '<button class="btn btn-sm btn-danger cancel-edit">X</button>'
        );

        // Focus sur le champ de saisie du libellé
        $row.find('input[name="edit-libelle"]').focus();
    });

    // Gestion du clic sur le bouton "Valider" pour la modification
    $('#structure-table').on('click', '.validate-edit', function() {
        var $row = $(this).closest('tr');
        var editUrl = $row.find('.btn-warning').attr('href'); // URL de modification récupérée depuis le lien

        var newLibelle = $row.find('input[name="edit-libelle"]').val();
        var newNombre = $row.find('input[name="edit-nombre"]').val();

        // Envoyer les données modifiées au serveur via AJAX
        $.ajax({
            url: editUrl,
            type: 'POST',
            data: {
                libelleStructure: newLibelle,
                nbStructure: newNombre
            },
            success: function(response) {
                // Mettre à jour la ligne dans le tableau avec les nouvelles données
                $row.find('td:nth-child(3)').text(newLibelle); // Mettre à jour le libellé
                $row.find('td:nth-child(4)').text(newNombre); // Mettre à jour le nombre

                // Restaurer les boutons d'action
                $row.find('td:nth-child(5)').html(
                    '<a href="#" class="btn btn-sm btn-warning">Modifier</a>' +
                    '<a href="#" class="btn btn-sm btn-danger delete-structure">Supprimer</a>'
                );

                // Retirer les champs de saisie
                $row.removeClass('editing'); // Retirer la classe d'édition
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la modification de la structure :', error);
                alert('Une erreur est survenue lors de la modification de la structure.');
            }
        });
    });

    // Gestion du clic sur le bouton "Annuler" pour la modification
    $('#structure-table').on('click', '.cancel-edit', function() {
        var $row = $(this).closest('tr');

        // Restaurer les valeurs initiales (avant modification)
        var originalLibelle = $row.find('input[name="edit-libelle"]').attr('value');
        var originalNombre = $row.find('input[name="edit-nombre"]').attr('value');

        // Réafficher les valeurs initiales dans les cellules du tableau
        $row.find('td:nth-child(3)').text(originalLibelle); // Libellé
        $row.find('td:nth-child(4)').text(originalNombre); // Nombre

        // Restaurer les boutons d'action
        $row.find('td:nth-child(5)').html(
            '<a href="#" class="btn btn-sm btn-warning">Modifier</a>' +
            '<a href="#" class="btn btn-sm btn-danger delete-structure">Supprimer</a>'
        );

        // Retirer les champs de saisie
        $row.removeClass('editing'); // Retirer la classe d'édition
    });

   ////////////////////////////////// Gestion du clic sur le bouton "Supprimer"////////////////////////////////////////////
    $('#structure-table').on('click', '.delete-structure', function(event) {
        event.preventDefault(); // Ensure this prevents default anchor behavior

        var deleteUrl = $(this).attr('href'); // URL de suppression récupérée depuis le lien
        var $row = $(this).closest('tr'); // Ligne du tableau à supprimer

        // Confirmation de la suppression
        if (confirm('Êtes-vous sûr de vouloir supprimer cette structure ?')) {
            // Envoyer la requête de suppression au serveur via AJAX
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    // Supprimer la ligne du tableau après la suppression réussie
                    $row.remove();
                    alert('Structure supprimée avec succès!');

                    // Optionnel : Recharger le tableau des structures
                    // reloadStructureTable();
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression de la structure :', error);
                    alert('Une erreur est survenue lors de la suppression de la structure.');
                }
            });
        }
    });

   //////////////////////////////// Fonction pour recharger le tableau des structures //////////////////////////////////
    function reloadStructureTable() {
        var url = $('#load-structures').data('url'); // Récupère l'URL depuis l'attribut data-url

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
    }

});
