<html>
	<head>
		<title> </title>
	</head>
	<body>
		<?php
			$dbhost = 'localhost';
			$dbuser = 'root';
			$dbpass= '';
			$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
			
			if(! $conn ) {
				die('Could not connect:'.mysql_error());
			}
			if ( $conn ) {
				echo 'data base connected sucsessufly';
			}
			
	
			if (! get_magic_quotes_gpc()) {
				$title = addslashes ($_POST['title']);
				$firstname = addslashes ($_POST['firstname']);
				$lastname = addslashes ($_POST['lastname']);
				$username = addslashes ($_POST['username']);
				$password = addslashes ($_POST['password']);
				$email = addslashes ($_POST['email']);
				$gender = addslashes ($_POST['gender']);
				$address = addslashes ($_POST['address']);
				$phone = addslashes ($_POST['phone']);
				$dob = addslashes ($_POST['dob']);
				$securityQuestion = addslashes ($_POST['securityQuestion']);
				$securityAnswer = addslashes ($_POST['securityAnswer']);
			} else {
				$title = $_POST['title'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$username = $_POST['username'];
				$password = $_POST['password'];
				$email = $_POST['email'];
				$gender = $_POST['gender'];
				$address = $_POST['address'];
				$phone = $_POST['phone'];
				$dob = $_POST['dob'];
				$securityQuestion = $_POST['securityQuestion'];
				$securityAnswer = $_POST['securityAnswer'];
			}	
			$sql = "INSERT INTO customer(Title, FirstName, LastName, UserName, Password, email, gender, address, phone, DOB, securityQuestion, securityAnswer) VALUES ('$title', '$firstname', '$lastname', '$username', '$password', '$email', '$gender', '$address', '$phone', '$dob', '$securityQuestion', '$securityAnswer');";
			echo $sql;
			
			echo "<br>";
			mysqli_select_db($conn, 'userinfo');
			
			if (!$retval = mysqli_query($conn, $sql)){
				echo "connection not succsesful";
			}
			
			if(! $retval ) {
				die("Could not enter data:". mysql_error());
			}
			
			echo "entered data succsesfully";
			echo "<br>";
			echo "<a href = 'index.html'>Go back</a>";
			mysqli_close($conn);
			
			
		?>
	</body>
</html> 