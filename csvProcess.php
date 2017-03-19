<?php
    //This code should be in top of every secure page.
    include 'secure.php';
    checkSession();
    include 'connection.php';
    include 'date.php';
    
    function isValidParty($party){
        return true; //as a test purpose this functionality is off now. 
        $validParty = "DMD DHAKA SHEHAB FMC ELITE";
        return strpos($validParty, $party) !== false ? true : false;
    }
    function isValidProduct($product){
        $validProduct = "MS SS1 SS1(S) SS1(P) LMS MTT MTT(P) KSO DIESEL SBPS";
        return strpos($validProduct, $product) !== false ? true : false;
    }
    function isValidQuantity($quantity){
        return is_numeric($quantity);
    }
    function isValidChallanProduct($challanProduct){
        if($challanProduct == '') return true;
        $validChallanProduct = "MS SS1 LMS MTT MTT(P) KSO DIESEL";
        return strpos($validChallanProduct, $challanProduct) !== false ? true : false;
    }
    
    if(isset($_POST['csvDate']) && isset($_POST['csv'])){
        //getting the date and csv of user into a variable and making all letters uppercase.
        $date = dateConverter($_POST['csvDate']);         // get it from user input & validate
        $csvString = trim(strtoupper($_POST['csv']));     // get it from user input & validate
        $queryStr = "INSERT INTO sales (date, party, product, qty, lorry, cName, cProduct) VALUES ";
        //-----end of user provided data 

        $eachRowData = explode("\n", $csvString);    //userinput turned into array
        $i = 1;
        foreach ($eachRowData as $key=>$value){
            //check if the line has exactly 5 comma
            substr_count($value, ",") == 5 ? 1 : die("wrong data format..! unusual amount of comma (,) found on line ".$i);
            $arrayOfColumns = explode(",", trim($value));       //each row exploded into columns 

            //each run of outer foreach will create a value set
            $queryStr .= "(";
            $queryStr .= "'".$date."'";

            foreach ($arrayOfColumns as $serial=>$item){
                // $serial: 0 for party, 1 for product, 2 for quantity, 3 for lorry, 4 for cName, 5 for cProduct
                //if data is not valid then the page will not redirect. else the page will be redirected to input page
                switch ($serial) {
                    case 0:
                        isValidParty($item) ? 1 : die("<strong>Error in row ". $i . ".</strong> Party not valid. ".$item);
                        break;
                    case 1:
                        isValidProduct($item) ? 1 : die("<strong>Error in row ". $i . ".</strong> Product not valid. ".$item);
                        break;
                    case 2:
                        isValidQuantity($item) ? 1 : die("<strong>Error in row ". $i . ".</strong> Quantity not valid. ".$item);
                        break;
                    case 5:
                        isValidChallanProduct($item) ? 1 : die("<strong>Error in row ". $i . ".</strong> Challan Product not valid. ".$item);
                        break;
                    case 6:
                        die("Something wrong in the line ".$item);
                        break;
                    default:
                        break;
                }
                // if all valid then build up the query string......
                $queryStr .=  ", '".$item."'";
            }

            //if the data set is at end then put ';' instead of ','
            $i++ == sizeof($eachRowData) ? $queryStr .= "); " : $queryStr .= "), ";
        }

        //query-1 (delete previous data of this date)
        $db->query("DELETE FROM sales WHERE date='".$date."'");
        
        //$queryStr is ready to be queried.
        $db->query($queryStr);
        //to avoid resubmission
		
		
		$time = isset($_POST['time']) ? $_POST['time'] : '0000-00-00 00:00:00';
		
        header("Location: dataInput.php?msg=1&page=**Data Input**&time=" . $time . "&csvdate=" . $_POST['csvDate'] );

    }  else {
       header("Location: dataInput.php"); 
    }
?>
    
    

