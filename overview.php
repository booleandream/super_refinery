<?php
include 'secure.php';
checkSession();
include 'date.php';
include 'connection.php';
//include 'saveuserdetail.php'; // This script will save ip, page and time into database

$partyList = array();

if (isset($_GET['from']) && isset($_GET['to'])) {
	$fromDate = dateConverter($_GET['from']); // the func does validation and validation
	$toDate = dateConverter($_GET['to']);

	function partyListForPeriod($fromDate, $toDate, $db) {
		$result = array(); // it contains party names.
		// special ORDER BY structure to show some parties at top
		$customPartyOrder = "CASE 
								WHEN party = 'POCL' THEN '1'
								WHEN party = 'MPL' THEN '2'
								WHEN party = 'JOCL' THEN '3'
								WHEN party = 'DMD' THEN '4'
								WHEN party = 'DHAKA' THEN '5'
								WHEN party = 'SHIHAB' THEN '6'
								WHEN party = 'ZIA' THEN '7'
								ELSE party END";
		//$dataObject = $db->prepare("SELECT DISTINCT party FROM sales WHERE date>=:from AND date<=:to ORDER BY party;");
		$dataObject = $db->prepare("SELECT DISTINCT party FROM sales WHERE date BETWEEN :from AND :to ORDER BY " . $customPartyOrder . ";");
		$dataObject->bindValue(':from', $fromDate);
		$dataObject->bindValue(':to', $toDate);
		$dataObject->execute();

		/*
		  $numOfResult = $dataObject->rowCount();
		  if($numOfResult < 1){
		  die("No Data");
		  }
		 * 
		 */

		while ($row = $dataObject->fetch(PDO::FETCH_NUM)) {
			array_push($result, $row[0]);
		}
		return $result;
	}

	$partyList = partyListForPeriod($fromDate, $toDate, $db);

	array_push($partyList, "Total:");

	$rowData = array();

	// running this loop once for every party. 
	for ($i = 0; $i < sizeof($partyList); $i++) {
		// echo $partyList[$i];
		$dataObject = $db->prepare("SELECT product, qty, party FROM sales WHERE party=:party AND date BETWEEN :from AND :to");
		$dataObject->bindValue(':party', $partyList[$i]);
		$dataObject->bindValue(':from', $fromDate);
		$dataObject->bindValue(':to', $toDate);
		$dataObject->execute();

		/*
		 * 		New product addition (edit only the following array)...!*
		 * 		Enter the new product name in the $productList array. Thats all...
		 */

		$productList = array('SBPS', 'MS', 'SS1', 'SS1(S)', 'SS1(P)', 'LMS', 'MTT', 'MTT(P)', 'KSO', 'DIESEL');

		$arr = array();
		/*
		 * injecting the total qty of products in the $arr. $arr will have total qty of product which the party received.
		 * It will not have any product which the party didnt received. such as POCL wont have SS1. 
		 * 
		 */
		while ($row = $dataObject->fetch(PDO::FETCH_ASSOC)) {
			array_key_exists($row['product'], $arr) ? ( $arr[$row['product']] += $row['qty'] ) : ( $arr[$row['product']] = $row['qty'] );
		}

		/*
		 * insering all products. the loop goes through the $arr and checks if it has all items. If not, insert that particular one.
		 */

		for ($j = 0; $j < sizeof($productList); $j++) {
			array_key_exists($productList[$j], $arr) ? 1 : $arr[$productList[$j]] = 0;
		}
		$rowData[$partyList[$i]] = $arr;
	}

	$totalOfAllTotal = 0;

	/* primarily, this loop assigns Total of individual sale to the array $rowData["Total:"]
	 * 
	 * secondarily it assigns value of Gross Total to the $totalOfAllTotal
	 */
	for ($i = 0; $i < sizeof($partyList); $i++) {
		if ($partyList[$i] == "Total:")
			continue;

		for ($j = 0; $j < sizeof($productList); $j++) {
			$rowData["Total:"][$productList[$j]] += $rowData[$partyList[$i]][$productList[$j]];
			$totalOfAllTotal += $rowData[$partyList[$i]][$productList[$j]];  //assigning gross total
		}
	}

	//start of the table
	$table = "<table class='table table-bordered table-hover table-condensed table-striped table-responsive' id='lastRowBold'>";

	/* VVI: construction of first row/heading. Heading must be in same order as $productList
	 * Party name, + ['MS', 'LMS', 'DIESEL', 'MTT', 'MTT(P)', 'SS1', 'KSO']
	 * 
	 * In each row 1st column will be drawn from $partyList and other 7 columns will be as $productList
	 */

	/* The following 5 lines of code constructs 1st line of table. */
	$table .= "<tr><td class='col-sm-2'><strong>Party</strong></td>";
	foreach ($productList as $product) {
		$table .= "<td class='col-sm-2'><strong>" . $product . "</strong></td>";
	}
	$table .= "<td class='col-sm-1'><strong>Total</strong></td></tr>";


	//outer for loop($i) will run once for each individual party.
	for ($i = 0; $i < sizeof($partyList); $i++) {
		$partyTotal = 0;

		$table .= "<tr><td align='center'>" . $partyList[$i] . "</td>";
		for ($j = 0; $j < sizeof($productList); $j++) {
			$table .= "<td>";
			$table .= $rowData[$partyList[$i]][$productList[$j]] == 0 ? '-' : number_format($rowData[$partyList[$i]][$productList[$j]]);

			//saving all product qty of a party in $partyTotal var
			$partyTotal += $rowData[$partyList[$i]][$productList[$j]];

			$table .= "</td>";
		}

		$table .= "<td>" . number_format($partyTotal) . "</td></tr>";
	}
	$table .= "</table>";

	/*
	 *      Table for sale %
	 * 
	 */
	$table2 = "<table class='table table-hover table-bordered table-condensed table-striped' id='table2'>";
	$table2 .= "<tr><td class='col-sm-2'><strong>Product Name</strong></td>       
                        <td align='center'  class='col-sm-1'><strong>Total&nbspSales</strong></td>
                        <td align='center' class='col-sm-1'><strong>Sale %</strong></td></tr>";

	//outer for loop($i) will run once for each individual party.

	$sumOfPerc = 0;
	for ($i = 0; $i < sizeof($productList); $i++) {
		$table2 .= "<tr><td>" . $productList[$i] . "</td>";  //1st column of row (name of product)

		$columnTwo = $rowData["Total:"][$productList[$i]] == 0 ? '-' : number_format($rowData["Total:"][$productList[$i]]);
		$table2 .= "<td align='right'>" . $columnTwo . "</td>";  //2nd column of row (total of that product)

		$perctage = $totalOfAllTotal == 0 ? 0 : number_format(($rowData["Total:"][$productList[$i]] / $totalOfAllTotal) * 100, 2);

		$columnThree = $perctage == 0 ? '-' : $perctage . '%';
		$table2 .= "<td align='right'>" . $columnThree . "</td>"; // % sales of that product)
		$table2 .= "</tr>"; // End of row

		$sumOfPerc += $perctage;
	}

	// rounding up to 100% in case of 99.99% and 100.01%
	if ($sumOfPerc > 99.98 && $sumOfPerc < 100)
		$sumOfPerc = 100;
	if ($sumOfPerc > 100 && $sumOfPerc < 100.02)
		$sumOfPerc = 100;

	$table2 .= "<tr align='right'><td>Grand Total</td><td>" . number_format($totalOfAllTotal) . "</td><td>" . $sumOfPerc . "%</td></tr>";
	$table2 .= "</table>";

	//vat table calculation This portion has no connection with the upper side regarding any variable. all r newly declared
	$cProductList = array('MS', 'LMS', 'DIESEL', 'MTT', 'MTT(P)', 'SS1', 'KSO');
	$dataObjectChallan = $db->prepare("SELECT cProduct, qty FROM sales WHERE date>=:from AND date<=:to AND cProduct IN ('MS', 'LMS', 'DIESEL', 'MTT', 'MTT(P)', 'SS1', 'SS1(P)', 'KSO')");
	$dataObjectChallan->bindValue(':from', $fromDate);
	$dataObjectChallan->bindValue(':to', $toDate);
	$dataObjectChallan->execute();

	$cProductTotals = array();
	$cTotal = 0;

	for ($i = 0; $i < sizeof($cProductList); $i++) {
		$cProductTotals[$cProductList[$i]] = 0;
	}

	while ($row = $dataObjectChallan->fetch(PDO::FETCH_NUM)) {
		$cProductTotals[$row[0]] += $row[1];
		$cTotal += $row[1];
	}

	//VAT table construction
	$table3 = "<table class='table table-hover table-bordered table-condensed table-striped' id='table3'>";
	$table3 .= "<tr><td class='col-sm-2'><strong>Product Name</strong></td>       
                        <td align='center'  class='col-sm-1'><strong>Total&nbspSales</strong></td>
                        <td align='center' class='col-sm-1'><strong>Sale %</strong></td></tr>";
	//outer for loop($i) will run once for each individual party.
	$sumPerc = 0;
	$perc = 0;

	for ($i = 0; $i < sizeof($cProductList); $i++) {
		$table3 .= "<tr><td>" . $cProductList[$i] . "</td>";  //1st column of row (name of product)


		$column2 = ($cProductTotals[$cProductList[$i]] == 0 ? '-' : $cProductTotals[$cProductList[$i]]);
		$table3 .= "<td align='right'>" . $column2 . "</td>";  //2nd column of row (total of that product)

		if ($cProductTotals[$cProductList[$i]] == 0) {
			$perc = 0;
		} else {
			$perc = number_format(($cProductTotals[$cProductList[$i]] / $cTotal) * 100, 2);
		}
		$column3 = $perc == 0 ? '-' : $perc . '%';
		$table3 .= "<td align='right'>" . $column3 . "</td>"; // % sales of that product)
		$table3 .= "</tr>"; // End of row
		$sumPerc += $perc;
	}
	// rounding up to 100% in case of 99.99% and 100.01%
	if ($sumPerc > 99.98 && $sumPerc < 100)
		$sumPerc = 100;
	if ($sumPerc > 100 && $sumPerc < 100.02)
		$sumPerc = 100;

	$table3 .= "<tr align='right'><td>Grand Total</td><td>" . $cTotal . "</td><td>" . $sumPerc . "%</td></tr>";
	$table3 .= "</table>";
	//$table3 .= $vatUpdatedUpto;
}
?>

<html>
    <head>
        <title>Super Refinery (pvt.) Limited</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- css -->
        <link rel="stylesheet" href="style/bootstrap.min.css">
        <link rel="stylesheet" href="style/datepicker.css">
        <link rel="stylesheet" href="style/overview.css">
        <link rel="stylesheet" href="style/print.css">
        <link rel="icon" type="image/x-icon" href="" />

        <!-- scripts -->
        <script src="js/jquery-1.11.3.js" defer></script>
        <script src="js/bootstrap.min.js" defer></script>
        <script src="js/bootstrap-datepicker.js" defer></script>
        <script src="js/overview_js.js" defer></script>
		<script src="js/shortcut.js"  defer></script>
    </head>
    <body>
        <div id="mainContainer">
            <nav class="navbar navbar-default">
                <div class="container">
                    <ul class="nav nav-pills">
                        <!-- <li><a href="home.php">Home</a></li> -->
                        <li><a href="detail.php">Detail</a></li>
                        <li class="active"><a href="overview.php">Overview</a></li>
                        <ul class="nav nav-pills" style="float: right">
							<li><a href="dataInput.php">Data Input</a></li>
                            <li id="logout"><a href="logout.php" style="margin-right: 5px; font-weight: bold;background: red;color: #000;">Logout</a></li>
                        </ul>
                    </ul>
                </div>
            </nav>

            <!--------------Input section start-------------------------------------------------------------------------------->
			<div class="container">
                <div class="row row-centered">
                    <div class="col-centered">
                        <form action="overview.php" id='form' class="form-inline" role="form" method="GET">
                            <!------------- From selector (date)-------------------------------------------------------->
                            <div class="form-group">
                                <label for="from">From: </label>
                                <strong><input type="text" name="from" value="<?php echo isset($_GET['from']) ? $_GET['from'] : '' ?>" id='from' class="datepicker form-control input-sm" data-date-format="dd-mm-yyyy" size="8" autocomplete="off"></strong>
                            </div>

                            <!-------------- To selector (date)--------------------------------------------------------->
                            <div class="form-group">
                                <label for="to">To: </label>
                                <strong><input type="text" name="to" value="<?php echo isset($_GET['to']) ? $_GET['to'] : '' ?>"id='to' class="datepicker form-control input-sm" data-date-format="dd-mm-yyyy" size="8" autocomplete="off"></strong>
                            </div>

                            <!------------- submit button --------------------------------------------------------------------->
                            <input type="submit" id="partyBtn" value="Submit" class="btn btn-primary">
                        </form>
                        <div  id="helpText" class="row-centered bg-danger">
                        </div>
                    </div>
                </div>

				<!------------- End of input section (form section)---------------------------------------------------------->

                <div id="printcontent">
					<div class="row row-centered" style="margin-bottom: 25px">
						<div class="col-sm-10 col-centered">
							<?php
							
							/*
							 * $partyList will contain only the "Total" if no party name is returned from sql. that
							 * means no data was found on the given dates.
							 */
							$validDates = sizeof($partyList) == 1 ? FALSE : TRUE;
							if( !$validDates ){
								print "No information found for the given dates.";
							}
							
							
							if (isset($table) && $validDates) {
								echo isset($_GET['from']) ? '<div class="table-top print"><div class="left">Partywise sales.</div><div class="right"><span class="pad2">Period:&nbsp' . $_GET['from'] : '';
								echo isset($_GET['to']) ? '&nbsp--&nbsp' . $_GET['to'] . '</span></div></div><br>' : '';
								print $table;
							}
							?>
						</div>
					</div>

					<div class='row row-centered' style="margin-bottom: 80px //outline: 1px solid black">
						<div class='col-centered' style="min-width: 100%;">

							<!-- as per operation -->
							<div class='col-sm-5 col-centered' style="vertical-align:top  //outline: 1px solid black;">
								<?php
								//getting max date upto where product is updated
								
								  $maxDateOps = $db->query("SELECT MAX(date) FROM sales");
								  $sqlDateOps = $maxDateOps->fetch(PDO::FETCH_NUM)[0];
								  $unixDateOps = strtotime($sqlDateOps);
								  $OpsUpdatedUpto = date('d M Y', $unixDateOps);
								 

								if (isset($table2) && $validDates) {
									echo isset($_GET['from']) ? '<div class="table-title print">Sale % of all products</div>' : '';
									print $table2;
									if ($toDate > $sqlDateOps) {
										echo "<div style='color:red; text-align:left;wdth:100%'>** Showing results upto $OpsUpdatedUpto</div>";
									}
								}
								?>
							</div>

							<!-- as per VAT -->
							<!--
							<div class='col-sm-5 col-centered' style="vertical-align: top;//outline: 1px solid black;"> 
							<?php
							/*
							  //getting max date upto where Challan product is updated
							  $maxDateVat = $db->query("SELECT MAX(date) FROM sales WHERE cProduct IN ('MS', 'LMS', 'DIESEL', 'MTT', 'MTT(P)', 'SS1', 'KSO')");
							  $sqlDateVat = $maxDateVat->fetch(PDO::FETCH_NUM)[0];
							  $unixDateVat = strtotime($sqlDateVat);
							  $vatUpdatedUpto = date('d M Y', $unixDateVat);

							  echo isset($_GET['from']) ? '<div class="table-vat">As per VAT Challan</div>': '';
							  if (isset($table3)) {
							  print $table3;
							  if($toDate > $sqlDateVat){
							  echo "<div style='color:red; text-align:left;wdth:100%'>** Showing results upto $vatUpdatedUpto</div>";
							  }
							  }
							 */
							?>
							</div>
							-->
						</div> 
					</div> 
                </div> 
            </div>
		</div>
		<div  id="myFooter">  
			<span class="glyphicon glyphicon-copyright-mark"></span> Sajib Biswas<br>
			<span class="glyphicon glyphicon-envelope"></span> sajibche@gmail.com
		</div>
    </body>
</html>