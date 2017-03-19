//for datepicker only

$(function () {
    $('.datepicker').datepicker().on('changeDate', function (e) {
            $(this).datepicker('hide');
            $("#to").val( $(this).val() );
            Default: false;
    });
});


