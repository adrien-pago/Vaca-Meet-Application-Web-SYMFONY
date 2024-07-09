$(document).ready(function() {
    $('#load-password-vaca').on('click', function(event) {
        event.preventDefault();

        const url = $(this).data('url');

        $.get(url, function(data) {
            $('#dynamic-content').html(data);
        });
    });

    $('#dynamic-content').on('submit', '#passwordVacaForm', function(event) {
        event.preventDefault();

        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();

        $.post('/password_vaca', {
            newPassword: newPassword,
            confirmPassword: confirmPassword
        })
        .done(function(response) {
            alert(response.success);
            location.reload();
        })
        .fail(function(xhr) {
            alert(xhr.responseJSON.error);
        });
    });
});
