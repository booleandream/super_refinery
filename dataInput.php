<?php
    include 'secure.php';
    checkSession();
	include 'connection.php'; 
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
        <link rel="stylesheet" href="style/dataInput.css">
        <link rel="icon" type="image/x-icon" href="" />
        <!-- scripts -->
        <script src="js/jquery-1.11.3.js"  defer></script>
        <script src="js/bootstrap.min.js"  defer></script>
        <script src="js/bootstrap-datepicker.js"  defer></script>
        <script src="js/datainput.js"  defer></script>
		<script src="js/shortcut.js"  defer></script>
    </head>
    
    <body>
<!--------------------------Navigation bar-------------------------------------------------------->      
        <nav class="navbar navbar-default">
            <div class="container">
                <ul class="nav nav-pills">
                    <!-- <li><a href="home.php">Home</a></li> -->
                    <li><a href="detail.php">Detail</a></li>
                    <li><a href="overview.php">Overview</a></li>
                    
                    <ul class="nav nav-pills" style="float: right">
						<li class="active"><a href="dataInput.php">Data Input</a></li>
                        <li id="logout"><a href="logout.php" style="font-weight: bold;background: red;color: #000;">Logout</a></li>
                    </ul>
                </ul>
            </div>
        </nav>
<!--------------------------End of Navigation area-------------------------------------------------------->


<!--------------------------user input collection section-1 (CSV input)-------------------------------------------------------->
        <div class="alert alert-info" role="alert">
            <div class="container">
                <div class="row row-centered">
                    <div class="col-xs-10 col-centered">        
                        <form action="csvProcess.php" id="csvForm" class="form" role="form" method="POST">
                            <!-- text input -->

                            <!-- 1. Textarea  CSV data -->
                            <div class="form-group col-xs-6" style="clear: both">
                                <label for="csvData" class="">Paste comma separated values here-</label>
                                <textarea name="csv" id="csvData" class="form-control" cols="30" rows='30'></textarea>
                            </div>

                            <!-- 1. Date input-->
                            <div class="form-group col-xs-2">
                                <label for="name" class="">Date</label>
                                <strong><input type="text" name='csvDate' id='date' placeholder="select date" class="datepicker form-control input-sm" data-date-format="dd-mm-yyyy" size="8" autocomplete="off"></strong>
                            </div>

                            <!-- Warning texts -->
                            <div class="col-xs-6">
                                <div class="panel panel-danger">
                                    <div class="panel-heading ">Please ensure the following: </div>
                                    <div class="panel-body" style="text-align: left">
                                        <ol style="padding: 0">
                                            <li>These are the total delivery detail of the mentioned date</li>
                                            <li>All data belongs to same date</li>
                                            <li>All the data of the given date will be replaced by the given CSV data.</li>
                                            <li>Date is correct.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>


                            <!-- button -->
                            <div class="form-group col-sm-6">
                                <button id="csvBtn" class="btn btn-primary btn-block userlog">OK.. Submit</button>
                                <?php 
                                    echo isset($_GET['msg']) && !empty($_GET['msg']) ? 'Success..!' : "";
                                ?>
                            </div>

                        </form>
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
