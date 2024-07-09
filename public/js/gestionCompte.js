document.addEventListener('DOMContentLoaded', function() {
    // Enregistrement des valeurs initiales au chargement de la page
    var initialNomCamping = document.getElementById('nomCamping').value;
    var initialSiret = document.getElementById('siret').value;
    var initialEmail = document.getElementById('email').value;

    document.getElementById('editNomCamping').addEventListener('click', function() {
        enableField('nomCamping');
    });

    document.getElementById('editSiret').addEventListener('click', function() {
        enableField('siret');
    });

    document.getElementById('editEmail').addEventListener('click', function() {
        enableField('email');
    });

    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        // Recharger la page pour restaurer les valeurs initiales
        window.location.reload();
    });

    function enableField(fieldName) {
        var field = document.getElementById(fieldName);
        field.readOnly = false;
        field.classList.remove('form-control-plaintext');
        field.classList.add('form-control');
        document.getElementById('saveChangesBtn').classList.remove('d-none');
        document.getElementById('cancelEditBtn').classList.remove('d-none');
    }

    // Gestion de la modification du mot de passe
    var passwordForm = document.getElementById('updatePasswordForm');
    passwordForm.addEventListener('submit', function(event) {
        var newPassword = document.getElementById('newPassword').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            event.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
        }
    });
});
