global.$ = global.jQuery = require('jquery');
require('bootstrap');
require('bootstrap-datepicker');
require('pc-bootstrap4-datetimepicker');

$.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
        time: 'fa fa-chevron-down',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-calendar-check',
        clear: 'fa fa-trash-alt',
        close: 'fa fa-times-circle'
    }
});

$(function () {

    $('.js-datepicker').datepicker({
        'format': 'dd/mm/yyyy'
    });

    $('.js-datetimepicker').datetimepicker({
        'format': 'DD/MM/YYYY HH:mm',
        'sideBySide': true
    });
});
