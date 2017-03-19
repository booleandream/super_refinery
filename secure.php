<?php

function checkSession(){
    session_start();
	
	
    if ( !isset($_SESSION['user']) ) {
		if( $_SESSION['user'] == '' ){
	        header("Location:index.php");
		}
    } else {
        include 'connection.php';
        $sql = $db->prepare( "SELECT * FROM users WHERE id=?" );
        $sql->execute( array( $_SESSION['user'] ) );
        while ($r = $sql->fetch()) {
            //echo "<center><h2>Hello, " . $r['username'] . "</h2>";
            //echo "<a href='logout.php'>Log Out</a></center>";
        }
    }
}