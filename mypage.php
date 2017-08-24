<?php 
session_start();
$title="My Page";
include_once("config.php"); // this is for defining the site root
include('includes/header.php'); 
include('includes/article-build-functions.php');

$which_id = "";

if(isset($_SESSION['user_session']))
{
	$which_id = $_SESSION['user_session'];
	$username = $_SESSION['call_them_name'];
}
else
{
	echo "<h1 id='sorrymessage'>Sorry, you're not logged in. Log in to view your profile!</h1>";
}

// to get array containing article info
try
{
	$stmt = $DB_con->prepare('SELECT * FROM Article WHERE article_id IN (SELECT article_id FROM Article_Like_History WHERE user_id = ?)');
	$stmt -> execute([$which_id]);
	$article_info = $stmt->fetchAll();
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

?>


<div class="wrapper">


<?php if($which_id !="")
{
	?>

<div class = "container-fluid" style = "background-color: #C1C4C3;">
	<h1 style="text-align: center;"> <?= strtoupper($username); ?>'S PAGE</h1>
	<p style = "text-align: center;">The place to find any articles you've hearted!</p>
</div>

<h3 style = "text-align: center;">LIKED ARTICLES</h3>

<!-- START OF THE LIKED ARTICLES SECTION -->
<div class = "container">

<?php

} //end if statement

?>


<?php

if(count($article_info)==0)
{
	echo "<p style = 'text-align: center;'>You haven't liked any articles yet!</p>";
}
else if (count($article_info) > 0) 
{
	for($i = 0; $i<count($article_info); $i++)
	{
		
		echo "<div class = 'row liked-articles-row' >
			<a href = '/articles/{$article_info[$i][article_type]}.php?id={$article_info[$i][article_id]}'>

				<div class = 'col-sm-2'>
					<img src = '{$article_info[$i][article_image_path]}' class = 'image-icon-size'>
				</div>
			
				

				<div class = 'col-sm-10'>
					<h4>{$article_info[$i][article_title]}</h4>
					<p>publish date: {$article_info[$i][article_date]}</p>
				</div>
			</a>
			</div>";
	}
}

?>

</div> <!-- END OF THE LIKED ARTICLES SECTION -->

<?php include('includes/footer.php'); ?>
