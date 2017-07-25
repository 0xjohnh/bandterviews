<?php
//handles request for like button
include('../includes/db-config.php'); 
include_once("../config.php");

$user_id = $_POST['user_id'];
$article_id = $_POST['article_id'];
$heart_was = $_POST['status'];


//was full, so now delete and decrement
if($heart_was === "full")
{

		$stmt = $DB_con->prepare('DELETE FROM Article_Like_History WHERE article_id = ? AND user_id = ?');
		$stmt -> execute(array($article_id, $user_id));

		$stmt2 = $DB_con->prepare('UPDATE Article SET article_like_count = article_like_count - 1 WHERE article_id = ?');
		$stmt2 -> execute(array($article_id));

		$now_change_to = "empty";

}
//was not full, so now insert and increment
elseif($heart_was === "empty")
{

		$stmt3 = $DB_con->prepare("INSERT INTO Article_Like_History (article_id, user_id) VALUES(?, ?)");
		$stmt3 -> execute(array($article_id, $user_id));

		$stmt4 = $DB_con->prepare('UPDATE Article SET article_like_count = article_like_count + 1 WHERE article_id = ?');
		$stmt4 -> execute(array($article_id));

		$now_change_to = "full";
	
}



echo $now_change_to;


?>