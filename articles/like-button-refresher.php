<?php

//updater. It'll receive the current state of the heart as it is displayed on page.
//Cross check
//Update if necessary

include('../includes/db-config.php'); 
include_once("../config.php");

$user_id = $_POST['user_id'];
$article_id = $_POST['article_id'];
$heart_current_state = $_POST['heart_state'];


$stmt = $DB_con->prepare('SELECT * FROM Article_Like_History WHERE article_id = ? AND user_id = ?');
$stmt -> execute(array($article_id, $user_id));
$user_record = $stmt->fetchAll();

if(empty($user_record))
{
	$should_be = "empty";
}
else 
{
	$should_be = "full";
}


if ($heart_current_state !== $should_be)
{
	echo "refresh";
} 



?>