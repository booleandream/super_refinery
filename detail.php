<?php
include 'secure.php';
checkSession();
include 'connection.php';   //db connection
include 'date.php';
//include 'saveuserdetail.php'; // This script will save ip, page and time into database
?>

<html>
    <head>
        <title>Super Refinery (pvt.) Limited</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- css -->
        <link rel="stylesheet" href="style/bootstrap.min.css">
        <link rel="stylesheet" href="style/datepicker.css">
        <link rel="stylesheet" href="style/detail.css">
        <link rel="stylesheet" href="style/print.css">
        <link rel="icon" type="image/x-icon" href="" />
        <!-- scripts -->
        <script src="js/jquery-1.11.3.js" defer></script>
        <script src="js/bootstrap.min.js" defer></script>
        <script src="js/bootstrap-datepicker.js" defer></script>
        <script src="js/detail.js" defer></script>
		<script src="js/shortcut.js"  defer></script>
    </head>
    <body>
        <div id="mainContainer">
            <nav class="navbar navbar-default">
                <div class="container">
                    <ul class="nav nav-pills">
                        <!-- <li><a href="home.php">Home</a></li> -->
                        <li class="active"><a href="detail.php">Detail</a></li>
                        <li><a href="overview.php">Overview</a></li>
                        <ul class="nav nav-pills" style="float: right">
							<li><a href="dataInput.php">Data Input</a></li>
                            <li id="logout"><a href="logout.php" style="font-weight: bold;background: red;color: #000;">Logout</a></li>
                        </ul>
                    </ul>
                </div>
            </nav>

            <!--------------Input section start-------------------------------------------------------------------------------->

            <div class="container">
                <div class="row row-centered">
                    <div class="col-centered">
                        <form action="detail.php" id='form' class="form-inline" role="form" method="GET">
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

                            <!-------------- Party selector (party name) ----------------------------------------------->
                            <div class="form-group">
                                <label for="sel1">Party:</label>
                                <select class="form-control" id="sel1" name="selectedParty">
                                    <option value='all'>All Party</option>
									<?php
									//---this portion gets unique party name and assigns them in a dropdown selector
									$query = "SELECT DISTINCT party FROM sales;";
									$dataObject = $db->query($query);
									while ($row = $dataObject->fetch(PDO::FETCH_NUM)) {
										//echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
										//if the selected option set and equal to current option, then make it selected. else parse as usual
										if (isset($_GET['selectedParty']) && $_GET['selectedParty'] == $row[0]) {
											echo "<option value='" . $row[0] . "' selected='selected'>" . $row[0] . "</option>";
										} else {
											echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
										}
									}
									?>
                                </select>
                            </div>


                            <!----- Product selector (Product name) ['MS', 'SS1', 'SS(S)', SS(P), "LMS", 'MTT', 'KSO', "DIESEL"]----->
                            <div class="form-group">
                                <label for="product">product:</label>
                                <select class="form-control" id="product" name="product">
								
								<?php
									// to add or delete a product from dropdown list, add/delete key-value pair in the array.
									$dropdownList = array(
										"All Products" => "all",
										"SBPS" => "SBPS",
										"MS" => "MS", 
										"SS1" => "SS1",
										"SS1(S)" => "SS1(S)",
										"SS1(P)" => "SS1(P)",
										"LMS" => "LMS",
										"MTT" => "MTT",
										"MTT(P)" => "MTT(P)",
										"KSO" => "KSO", 
										"DIESEL" => "DIESEL",
									);
									
									// flag will contain which product is currently selected.
									

									foreach ($dropdownList as $showVal => $realVal) {
										$flag = "";
										if(isset($_GET['product']) && $_GET['product'] == $showVal){
											$flag = "selected='selected'";
										}
										echo "<option value='{$realVal}' {$flag}>{$showVal}</option>";
									}
								?>
									
                                </select>
                            </div>

                            <!------------- submit button --------------------------------------------------------------------->
                            <input type="submit" id="partyBtn" value="Submit" class="btn btn-primary">
                        </form>
                        <div  id="helpText" class="row-centered bg-danger">
                        </div>
                    </div>
                </div>
				<!------------- End of input section (form section)---------------------------------------------------------->

                <div class="row row-centered" id="printcontent">
                    <div class="col-sm-8 col-centered">

						<?php
						if (isset($_GET['from'])) {
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

							// special ORDER BY structure to show specific order for products
							$customProductOrder = "CASE
												WHEN product = 'SS1' THEN '1'
												WHEN product = 'SS1(S)' THEN '2' 
												WHEN product = 'SS1(P)' THEN '3'
												WHEN product = 'MTT' THEN '4'
												WHEN product = 'KSO' THEN '5'
												WHEN product = 'MS' THEN '6'
												WHEN product = 'LMS' THEN '7'
												WHEN product = 'DIESEL' THEN '8'
												ELSE product END";

							$fromDate = dateConverter($_GET['from']);
							$toDate = dateConverter($_GET['to']);
							$selectedParty = $_GET['selectedParty'];
							$product = $_GET['product'];

							$commonStr = "SELECT DATE_FORMAT(date,'%d %b %y') as delDate, party, product, qty, lorry FROM sales ";  //1st part/common part of all query

							if ($selectedParty == 'all' && $product == 'all') {
								$query = $commonStr . "WHERE date BETWEEN :from AND :to ORDER BY date, " . $customPartyOrder . "," . $customProductOrder;
								$dataObject = $db->prepare($query);
								// only date to be binded
							} elseif ($selectedParty !== 'all' && $product == 'all') {
								$query = $commonStr . "WHERE date BETWEEN :from AND :to AND party=:userInput ORDER BY date," . $customProductOrder;
								$dataObject = $db->prepare($query);
								$dataObject->bindValue(':userInput', $selectedParty);
								// "date" & "party Name" needed. Party name binded.
							} elseif ($selectedParty == 'all' && $product !== 'all') {
								$query = $commonStr . "WHERE date BETWEEN :from AND :to AND product=:product ORDER BY date, " . $customPartyOrder;
								$dataObject = $db->prepare($query);
								$dataObject->bindValue(':product', $product);
								// "date" & "product Name" needed. Product name binded.
							} elseif ($selectedParty !== 'all' && $product !== 'all') {
								$query = $commonStr . "WHERE date BETWEEN :from AND :to AND party=:userInput AND product=:product ORDER BY date";
								$dataObject = $db->prepare($query);
								$dataObject->bindValue(':userInput', $selectedParty);
								$dataObject->bindValue(':product', $product);

								// "date" "product name" and "party Name" needed. Party name product name binded.
							}
							// binding date of from&to
							$dataObject->bindValue(':from', $fromDate);
							$dataObject->bindValue(':to', $toDate);

							$dataObject->execute();

							//start of the table
							$tmp = "<table class='table table-bordered table-hover table-condensed table-striped' id='lastRowBold'>";
							//construction of first row/heading 
							$tmp .= "<tr>

                                                    <td class='col-sm-1'><strong>Sl.</strong></td>       
                                                    <td class='col-sm-1'><strong>Date</strong></td>
                                                    <td class='col-sm-1'><strong>Party</strong></td>
                                                    <td class='col-sm-1'><strong>Product</strong></td>
                                                    <td class='col-sm-1'><strong>Quantity</strong></td>
                                                    <td class='col-sm-1'><strong>Lorry No.</strong></td>
                                                    </tr>";

							$arr = array();  // arr will contain total of all products
							$count = 1;   // for assigning rowcount at the 1st column of each row.
							$total = 0;
							
							$date;
							
							while ($row = $dataObject->fetch(PDO::FETCH_ASSOC)) {
								//This portion checks date change for rows.
								$date = $count == 1 ? $row["delDate"] : $date;
								
								if($date != $row["delDate"]){
									$date = $row["delDate"];
									$tmp .= '<tr><td colspan="6" id="separator"></td></tr>';
								}
								
								//
								//
								//check if $arr has key of name ms/ss1/etc, if not assign 
								array_key_exists($row['product'], $arr) ? ( $arr[$row['product']] += $row['qty'] ) : ( $arr[$row['product']] = $row['qty'] );
								$total += $row['qty'];   // getting qty of each row to be summed in $total

								$tmp .= "<tr>"; //row start
								$tmp .= "<td>" . $count++ . "</td>";
								foreach ($row as $key => $value) {
									$tmp .= "<td nowrap>" . $value . "</td>";
								}
								$tmp .= "</tr>"; //row end
							}

							$tmp .= "</table>"; //table end




							$summary = '<table>';




							foreach ($arr as $key => $value) {
								$summary .= '<tr>';
								$summary .= '<td>' . $key . ': </td><td>' . number_format($value) . ' Ltr </td><td><span class="badge">' . number_format($value / 9000, 1) . '</span></td>';
								$summary .= '</tr>';
							}

							$summary .= "<tr><td>Total delivery: </td><td>" . number_format($total) . ' Ltr </td><td><span class="badge">' . number_format($total / 9000, 1) . '</span></td></tr>';

							$summary .= '</table>';

							$notification = '';
							$notification .= '<div class="panel panel-primary" style="margin-bottom:100px"><div class="panel-heading">';
							$notification .= "Total: " . $dataObject->rowCount() . " records found.</div>";
							$notification .= '<div class="panel-body"><div id="summarytable">' . $summary . '</div></div></div>';

							if ($total != 0) {
								echo $tmp;
								echo $notification;
							} else {
								echo("No information found for the given dates.");
							}
						}
						?>

                    </div>
                </div>
            </div>
            <div  id="myFooter">  
                <span class="glyphicon glyphicon-copyright-mark"></span> Sajib Biswas<br>
                <span class="glyphicon glyphicon-envelope"></span> sajibche@gmail.com
            </div>
        </div>
    </body>
</html>