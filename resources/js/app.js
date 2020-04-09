require('./bootstrap');


/* Override Alert */
window.old_alert = window.alert;
window.alert = function(message, title='Attenzione!'){
    let $modal = $('#modal-alert');
    $modal.find('.modal-title').text(title);
    $modal.find('.modal-body').text(message);
    $modal.modal('show');
};

$(document).ready(function() {

    /* jQuery Validation Plugin - Bootstrap */
    $.validator.setDefaults({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    window.dataTableDefaultOptions = {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/Italian.json'
        }
    };

    $('.dataTable').DataTable(window.dataTableDefaultOptions);


    $('.select2').select2({
        theme: 'bootstrap4',
    });

    // Fix filename in file browser
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

});