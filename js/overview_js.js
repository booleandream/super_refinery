$(document).ready(function () {
    
	//getting user time and page name
	var d = new Date();
	var time = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
	$("#form").append('<input name="time" id="date" style="display: none">');
	$('#date').attr("value", time);
	// End of date time and page name collection functionality
	
	$("#logout").click(function(){
		$("body").css("display", "none");
	});
	
	$("#lastRowBold tr:first").css({backgroundColor: '#FFA62F', fontWeight: 'bolder'});
	$("#lastRowBold tr:last").css({fontWeight: 'bolder'});
	$("#lastRowBold td").css({textAlign: 'right'});
	$("#lastRowBold tr td:first-child").css({textAlign: 'left'});
	$("#lastRowBold tr:first td").css({textAlign: 'center'});
	$("#centerAlign td").css({textAlign: 'center'});


	$("#table2 tr:first").css({backgroundColor: '#FFA62F', fontWeight: 'bolder'});
	$("#table2 tr:last").css({fontWeight: 'bolder'});
	$("#table2 td").css({textAlign: 'right'});
	$("#table2 tr td:first-child").css({textAlign: 'left'});
	$("#table2 tr:first td").css({textAlign: 'center'});
	$("#table2").css({marginBottom: '10px'});

	$("#table3 tr:first").css({backgroundColor: '#41CC52', fontWeight: 'bolder'});
	$("#table3 tr:last").css({fontWeight: 'bolder'});
	$("#table3 td").css({textAlign: 'right'});
	$("#table3 tr td:first-child").css({textAlign: 'left'});
	$("#table3 tr:first td").css({textAlign: 'center'});
	$("#table3").css({marginBottom: '10px'});


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

	var from = document.getElementById("from");

	var partyBtn = document.getElementById("partyBtn");

	partyBtn.onclick = function (e) {
		e.preventDefault();

		var form = document.getElementById("form");
		var from = document.getElementById("from");
		var to = document.getElementById("to");
		var helpText = document.getElementById("helpText");
		var myFooter = document.getElementsByClassName("myFooter");

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


