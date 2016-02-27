function addFormDeleteLink($formLi) {
    var $removeFormA = $('<div class="form-group"><a href="#" class="btn btn-danger"><i class="fa fa-remove"></i> Supprimer</a></div>');
    $formLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $formLi.remove();
    });
}

function addForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addFormDeleteLink($newFormLi);
}

var $collectionHolder;
var $addLink = $('<a href="#" class="add_tag_link btn btn-warning"><i class="fa fa-plus"></i> Ajouter une limite</a>');
var $newLinkLi = $('<li></li>').append($addLink);

$(document).ready(function() {
    $collectionHolder = $('ul.limits');
    $collectionHolder.append($newLinkLi);

    $collectionHolder.find('li.former').each(function() {
        addFormDeleteLink($(this));
    });

    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addLink.on('click', function(e) {
        e.preventDefault();
        addForm($collectionHolder, $newLinkLi);
    });

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
                ressourceToDelete.remove();
            },
            error: function() {
                handleResponse("danger", "Une erreur est survenue");
            }
        });
    });

    $(".ajax-form").on("submit", function(e) {
        e.preventDefault();

        var data = $(this).serialize(),
            url = $(this).attr("action"),
            form = $(this);

        $.ajax({
            url: url,
            method: "POST",
            data: data,
            success: function() {
                handleResponse("success", "Le jeu à bien été ajouté");
                form.find('.btn-warning').prop('disabled', true);
            },
            error: function() {
                handleResponse("danger", "Une erreur est survenue");
            }
        });
    });
});
