<?php     
//gets current like count

include('../includes/db-config.php'); 
include_once("../config.php");

$article_id = $_POST['article_id'];
$heart_current_state = $_POST['heart_state'];

$stmt = $DB_con -> prepare('SELECT * FROM Article WHERE article_id = ?');
$stmt -> execute([$article_id]);
$like_count = $stmt->fetchAll();

echo $like_count[0][article_like_count];

?>