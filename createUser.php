<?php
	// BR0001 - Create User

	// 1. Accept post request
	$fname = isset($_POST["fname"]) ? $_POST["fname"] : null;
	$lname = isset($_POST["lname"]) ? $_POST["lname"] : null;
	$email = isset($_POST["email"]) ? $_POST["email"] : null;
	$password = isset($_POST["password"]) ? $_POST["password"] : null;
	$re_password = isset($_POST["re_password"]) ? $_POST["re_password"] : null;
	$flag = false;

	// 2. Validation post request
	if (empty($fname)) {
		$flag = true;
		echo json_encode(
			[
				"message" => "First name is required.",
				"error" => "0001"
			]
		);
	}

	if (empty($lname)) {
		$flag = true;
		echo json_encode(
			[
				"message" => "Last name is required.",
				"error" => "0002"
			]
		);
	}

	if (empty($email)) {
		$flag = true;
		echo json_encode(
			[
				"message" => "Email is required.",
				"error" => "0003"
			]
		);
	}

	if (empty($password)) {
		$flag = true;
		echo json_encode(
			[
				"message" => "Password is required.",
				"error" => "0004"
			]
		);
	}

	if ($password != $re_password) {
		$flag = true;
		echo json_encode(
			[
				"message" => "Password do not match.",
				"error" => "0005"
			]
		);
	}

	// 3. Insert to users table
	$host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "sad";
	// Connect to database
	$connection = mysqli_connect($host, $db_user, $db_pass, $db_name);
	// Validate connection
	if (mysqli_connect_errno()) {
		$flag = true;
		echo json_encode(
			[
				"message" => "Could not connect to database.",
				"error" => "0006"
			]
		);
	}

	// Create INSERT SQL
	$sql = "INSERT INTO users(
				fname, 
				lname, 
				email, 
				password
			) VALUES (
				'" . $fname . "',
				'" . $lname . "',
				'" . $email . "',
				'" . md5($password) . "'
			)";

	// Validate query
	if (
		!$flag &&
		mysqli_query($connection, $sql)
	) {
		// if successful insert
		// Get inserted Id
		$insert_id = mysqli_insert_id($connection);
		// Create select sql to view inserted record
		$sql_view = "SELECT * FROM users WHERE id=" . $insert_id;
		// Execute select query and store to a variable
		$result = mysqli_query($connection, $sql_view);
		// Convert object result variable to associative array
		$result_arr = mysqli_fetch_array($result, MYSQLI_ASSOC);
		// return created user;
		echo json_encode($result_arr);
	} else {
		// else fail insert
		echo json_encode(
			[
				"message" => "Insert user failed.",
				"error" => "0007"
			]
		);
	}

	mysqli_close($connection);
?>