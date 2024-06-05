$(document).ready(function (e) {
    $('#filter-date input,#id_plant').on('change', function () {
        $(this).parents('form').submit();
    });
});