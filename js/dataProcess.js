//for datepicker only

$(function(){
    $('.datepicker').datepicker().on('changeDate', function(e){
        $(this).datepicker('hide');
    });
});

//----datepicker end-------

window.onload = function(){
    var submitBtn = document.getElementById("submitBtn");
    
    submitBtn.onclick = function(e){
        e.preventDefault();

        var form = document.getElementById("form");
        var date = document.getElementById("name");
        var challanName = document.getElementById("challanName");
        var challanProduct = document.getElementById("challanProduct");
        var partyName = document.getElementById("partyName");
        var actProduct = document.getElementById("actProduct");
        
        if(date.value.length < 5){
            alert("date not valid");
            return;
        }
        
        /* These two validation are ignored temporarily
         * 
        if(challanName.value.length < 3){
            alert("Challan Name not valid");
            return;
        }
        if(challanProduct.value.length < 2){
            alert("Challan Product not valid");e.preventDefault();
            return;
        }
        
        */
       
        if(partyName.value.length < 3){
            alert("Party name not valid");e.preventDefault();
            return;
        }
        
        if(actProduct.value.length < 2){
            alert("act. product name not valid");e.preventDefault();
            return;
        }
        mainForm.submit();
    };

};
