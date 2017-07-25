<?php
require_once ROOT.'includes/db-config.php';

//if this is an article or an interview, get the id
if(isset($_GET['id']))
{
    $id = $_GET['id'];


  //use the id to get all info, put into $result
  try
  {
    $stmt = $DB_con->prepare('SELECT * FROM Article WHERE article_id = ?');
    $stmt -> execute([$id]);
    $result = $stmt->fetchAll();
  }
  catch(PDOException $e)
  {
    echo $e->getMessage();
  }

  //get the required variables
  $title = $result[0][article_title];
  $type  = $result[0][article_type];

}

?>


<!DOCTYPE html>
 <html lang="en"> 

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <?php echo "<title>$title</title>"; ?>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


  <!--     MY STYLES  -->
  <link href="/css/styles.css" type="text/css" rel="stylesheet">


<?php 
//depending on which type, link to the right stylesheet
  if($type==="interview")
  {
    echo "<link href = '/css/article-interview-styles.css' type = 'text/css' rel = 'stylesheet'>";
  }
  elseif($type==='article')
  {
    echo "<link href = '/css/article-article-styles.css' type = 'text/css' rel = 'stylesheet'>";
  }

?>

</head>

<body>


<?php include(ROOT.'includes/nav.php'); ?>

