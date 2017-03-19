$(document).ready(function(){
	
	//getting user time and page name
	var d = new Date();
	var time = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
	$("#form").append('<input name="time" id="date" style="display: none">');
	$('#date').attr("value", time);
	// End of date time and page name collection functionality
	
 });