global.$ = global.jQuery = require('jquery');
require('bootstrap');
require('bootstrap-datepicker');

$(function(){
    $('.js-datepicker').datepicker({
        'format': 'dd/mm/yyyy'
    });
});
