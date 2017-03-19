<html>
    <head>
        <title>Super Refinery (pvt.) Limited</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- css -->
        <link rel="stylesheet" href="style/bootstrap.min.css">
        <link rel="stylesheet" href="style/style.css">
        <link rel="icon" type="image/x-icon" href="" />
        <!-- scripts -->
        <script src="js/jquery-1.11.3.js" defer></script>
        <script src="js/bootstrap.min.js" defer></script>
        <script src="js/index.js" defer></script>
    </head>

    <body>
        <div class="wrapper">
            <form class="form-signin" method="POST" id="form">       
                <h2 class="form-signin-heading"> </h2>
                <input type="text" class="form-control" name="mail" placeholder="User Name" autofocus="" required="" readonly onfocus="this.removeAttribute('readonly');"/>
                <input type="password" class="form-control" name="pass" placeholder="Password" required="" readonly onfocus="this.removeAttribute('readonly');"/>      
                <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   

                <?php
                    session_start();
                    include 'connection.php';
					
					if ( isset($_POST['mail']) && isset($_POST['pass']) ) {
						$time = isset($_POST['time']) ? $_POST['time'] : '0000-00-00 00:00:00';
						$email = $_POST['mail'];
						//$email = "";
						$password = $_POST['pass'];
						$sql = $db->prepare("SELECT id,password,psalt FROM users WHERE username=:user");
						$sql->bindValue(':user', $email);
						$sql->execute();

						while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
							$p = $row['password'];
							$p_salt = $row['psalt'];
							$id = $row['id'];
						}

						if(isset($p)){

							if ($p == $password) {
								//generate a random number
								//store the number in db
								//store the number both in browser session
								$_SESSION['user'] = $id;
								$_SESSION['id'] = $email;

								//redirect to welcome page
								
								$username = "";
								
								header("Location:detail.php?page=Signed In&time=". $time . "&username=" . $username);
							} else {
								echo "<p style='color:red;'>Username/Password is Incorrect.</p>";
							}
						}else{
							echo "<p style='color:red;'>Username/Password is Incorrect.</p>";
						}
					}  else {
						session_destroy();
					}
                ?>
            </form>
        </div>
    </body>
</html>
