$(document).ready(function() {
   window.ressourceToDelete = "";

    $('body').on('click', '.btn-delete', function(e) {
        e.preventDefault();

        var url = $(this).attr('href'),
           entity = $(this).data('entity'),
           modal = $('#deleteModal');

        modal.data('url', url);
        modal.find('.modal-title span').text(entity);
        modal.modal("show");
        window.ressourceToDelete = $(this).closest('.behive-ressource');
    });

    function handleResponse(status, msg) {
        var modalInfo = '<div class="alert alert-'+status+'">'+msg+'</div>';
        $('#deleteModal').modal('hide');
        $('.alert').remove();
        $('#page-wrapper').prepend(modalInfo);
    }

    $('body').on('click', '.btn-delete-valid', function(e) {
        e.preventDefault();

        var ressourceToDelete = window.ressourceToDelete;
        $.ajax({
            url: $('#deleteModal').data('url'),
            method: "GET",
            success: function() {
                handleResponse("success", "La ressource à bien été supprimé");
                console.log(ressourceToDelete);
                ressourceToDelete.remove();
            },
            error: function() {
                handleResponse("danger", "Une erreur est survenue");
            }
        });
    });
});
