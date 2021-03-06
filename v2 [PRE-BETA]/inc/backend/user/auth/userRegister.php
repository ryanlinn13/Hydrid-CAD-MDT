<?php
require '../../../connect.php';
require '../../../config.php';
header('Content-Type: application/json');
$username       	    = !empty($_POST['username']) ? trim($_POST['username']) : null;
$email       	        = !empty($_POST['email']) ? trim($_POST['email']) : null;
$pass                   = !empty($_POST['password']) ? trim($_POST['password']) : null;

$username      			= strip_tags($username);
$email      			= strip_tags($email);
$pass      	            = strip_tags($pass);

$error = array();

if (strlen($pass) < 6) {
	$error['msg'] = "Please use a longer password.";
	echo json_encode($error);
	exit();
}
elseif (strlen($pass) > 120) {
	$error['msg'] = "Please use a shorter password.";
	echo json_encode($error);
	exit();
}
elseif (strlen($username) > 36) {
	$error['msg'] = "Please use a shorter username.";
	echo json_encode($error);
	exit();
}

// Check if email is taken
$sql  = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $error['msg'] = "That email is already taken.";
	echo json_encode($error);
	exit();
}

$sql2  = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindValue(':username', $username);
$stmt2->execute();
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $error['msg'] = "That username is already taken.";
	echo json_encode($error);
	exit();
}

$passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
$sql3          = "INSERT INTO users (username, email, password, usergroup, join_date, join_ip) VALUES (:username, :email, :password, :usergroup, :join_date, :join_ip)";
$stmt3         = $pdo->prepare($sql3);
$stmt3->bindValue(':username', $username);
$stmt3->bindValue(':email', $email);
$stmt3->bindValue(':password', $passwordHash);
$stmt3->bindValue(':usergroup', 'Unverified');
$stmt3->bindValue(':join_date', $us_date);
$stmt3->bindValue(':join_ip', $ip);
$result = $stmt3->execute();
if ($result) {
    $error['msg'] = "";
	echo json_encode($error);
	exit();
}