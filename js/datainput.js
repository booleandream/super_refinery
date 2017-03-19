//for datepicker only
$(function () {
    //getting user time and page name
    var d = new Date();
    var time = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    $("#csvForm").append('<input name="time" id="datetime" style="display: none">');
    $('#datetime').attr("value", time);
    // End of date time and page name collection functionality

    $('.datepicker').datepicker().on('changeDate', function (e) {
        $(this).datepicker('hide');
    });

    $("#logout").click(function () {
        $("body").css("display", "none");
    });

});

//----datepicker end-------
window.onload = function () {
    var myFooter = document.getElementById("myFooter");
    myFooter.style.opacity = '1';
    myFooter.style.right = '0px';

    var submitBtn = document.getElementById("csvBtn");

    submitBtn.onclick = function (e) {
        e.preventDefault();

        var csvForm = document.getElementById("csvForm");
        var date = document.getElementById("date");
        var csvData = document.getElementById("csvData");

        if (date.value.length < 5) {
            alert("date not valid");
            return;
        }
        if (csvData.value.length < 1) {
            alert("No CSV data provided.");
            return;
        }
        csvForm.submit();
    };

};
