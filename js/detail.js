$(document).ready(function () {

    //getting user time and page name
    var d = new Date();
    var time = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    $("#form").append('<input name="time" id="date" style="display: none">');
    $('#date').attr("value", time);
    // End of date time and page name collection functionality



    $("#lastRowBold tr:first").css({backgroundColor: '#FFA62F'});
    $("#lastRowBold tr:last").css({});
    $("#lastRowBold td").css({textAlign: 'right'});
    $("#lastRowBold tr td:first-child").css({textAlign: 'center'});
    $("#lastRowBold tr td:nth-child(2)").css({textAlign: 'center'});
    $("#centerAlign td").css({textAlign: 'center'});

    $("tr:first").css({backgroundColor: '#FFA62F', fontWeight: 'bolder'});

    $("#logout").click(function () {
        $("body").css("display", "none");
    });
});

//for datepicker only
$(function () {
    $('.datepicker').datepicker().on('changeDate', function (e) {
        $(this).datepicker('hide');
        //$("#to").val( $(this).val() );
    });
});
//----datepicker end-------

window.onload = function () {
    var myFooter = document.getElementById("myFooter");
    myFooter.style.opacity = '1';
    myFooter.style.right = '0px';

    document.getElementById('mainContainer').style.display = "block";


    var partyBtn = document.getElementById("partyBtn");

    partyBtn.onclick = function (e) {
        e.preventDefault();

        var form = document.getElementById("form");
        var to = document.getElementById("to");
        var helpText = document.getElementById("helpText");


        if (from.value.length === 10 && to.value.length === 10) {
            form.submit();
            helpText.style.display = "none";
        } else {
            helpText.style.display = "block";
            helpText.textContent = "invalid Date..!";
            return;
        }



    };

};


