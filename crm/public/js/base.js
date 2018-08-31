var $collectionHolder;

var $addButton = $('<button type="button" class="add_tag_link btn btn-lg btn-primary">Ajouter une adresse</button>');
var $newLinkLi = $('<li class="list-group-item"></li>').append($addButton);

jQuery(document).ready(function () {
    $collectionHolder = $('ul.addresses');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addButton.on('click', function (e) {
        addForm($collectionHolder, $newLinkLi);
    });
    $collectionHolder.find('li.address').each(function () {
        addDeleteLink($(this));
    });
});

function addForm() {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li class="address list-group-item"></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addDeleteLink($newFormLi);
}

function addDeleteLink($formLi) {
    var $removeFormButton = $('<div class="mt-2"><button type="button" class="btn btn-danger">Supprimer cette adresse</button></div>');
    $formLi.append($removeFormButton);
    $removeFormButton.on('click', function (e) {
        $formLi.remove();
    });
}