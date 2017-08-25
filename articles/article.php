<?php
session_start();

include_once("../config.php"); // this is for defining the site root
include('../includes/header.php'); 
require_once ROOT.'includes/article-build-functions.php';


if( isset($_GET['id']))
{
	$which_id = $_GET['id'];
} 
else 
{
	echo "<h1 id='sorrymessage'>Sorry, you're trying to read an article that doesn't exist!</h1>";
	echo "<p id='sorrymessage'> <a href='../index.php'>Click here to go back to the home page</a></p>";
}


try
{
	$stmt = $DB_con->prepare('SELECT * FROM Article WHERE article_id = ?');
	$stmt -> execute([$which_id]);
	$result = $stmt->fetchAll();
}
catch(PDOException $e)
{
	echo $e->getMessage();
}


//DEFINE CURRENT ARTICLE ID
$current_article_id = $result[0][article_id];

//DEFINE CURRENT USER ID AS EMPTY (AS OF NOW)
$current_user_id = "";




if($user->is_loggedin()!="")
{


	//DEFINE CURRENT USER ID
	$current_user_id = $_SESSION['user_session'];


	// DB QUERY START - gets row to see if user already liked the current article
	try
	{

		$stmt = $DB_con->prepare('SELECT * FROM Article_Like_History WHERE article_id = ? AND user_id = ?');
		$stmt -> execute(array($current_article_id, $current_user_id));
		$result2 = $stmt->fetchAll();

	}
	catch(PDOException $e)
	{
	 echo $e->getMessage();
	}
	// DB QUERY END

	//if empty then they don't like 
	if(empty($result2)){
		$heart_is_empty = true;
		
	} 
	else // they do like/exist
	{ 
		$heart_is_empty = false;
	}

	
}



//define path to .txt file, clean it
$path_to_text = $result[0][article_body_path]; 
cleanString($path_to_text);

//make array $lines of all lines of article
$lines = file($path_to_text, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


$photoCredits = $lines[0];

?>

<h1 class = "very-top" id = "title"><?= $result[0][article_title] ?></h1>



<div class = "wrapper">

<!-- insert article image (if any)-->

<div class = "container" >

	<?php

		//if the picture doesn't exist then don't display it.
		if($result[0][article_image_path]!=='')
		{

			$image_src = $result[0][article_image_path];

			echo "<img src = {$image_src} alt = 'article-image' id = 'article-image' class= 'center-block'> 

			<p style = 'text-align: center;'><span class ='glyphicon glyphicon-camera' id='pewter' ></span> by: {$photoCredits}</p>";

		}

	?>

</div>

<p id="date" class="very-top" style="padding-bottom: 40px;">Published on <?= format_date($result[0][article_date]); ?> in articles</p>


<script>


$(document).ready(function(){

	var userid = '<?= $current_user_id ?>';
	
	var loggedin = false;
	if(userid!=='')
	{
		loggedin = true;
	}

	//updates the state of the like button (empty or full)
	function autoRefresher(){
	

			if( $("#heart").attr("class") === "glyphicon glyphicon-heart" )
			{
				heart_current_state="full";
			}
			else 
			{
				heart_current_state="empty";
			}

			$.post("like-button-refresher.php",  
			{
				user_id: '<?= $current_user_id ?>',
				article_id: '<?= $current_article_id ?>',
				heart_state: heart_current_state
			},
			function(action, status) //upon success, 
			{
				if(action === "refresh")
				{
					if(heart_current_state === "full")
					{
						$("#heart").removeClass("glyphicon-heart");
						$("#heart").addClass("glyphicon-heart-empty");
					}
					else
					{
						$("#heart").removeClass("glyphicon-heart-empty");
						$("#heart").addClass("glyphicon-heart");
					}

				}

			});
		

		//updates the like count
		$.post("like-button-like-count.php",  
		{
			article_id: '<?= $current_article_id ?>'
		},

		function(likecount, status) //upon success, 
		{			
			var ending = "";

			if(likecount != "1"){ ending = "s"; }

			$("#likecount").text("this article has "+likecount + " like" + ending);

			if(likecount == "0")
			{
				$("#likecount").text("no likes (yet)");
			}
			else
			{
				$("#likecount").text(""+likecount + " like" + ending);
			}


		});

	}

	setInterval(function(){autoRefresher()}, 1000);


	var heart_was;
	var button_disabled = false;

	if(loggedin)
	{
		$("#like-button").click(function(){


			if(button_disabled === false && loggedin)
			{
			
				button_disabled = true;
				
				if( $("#heart").attr("class") === "glyphicon glyphicon-heart" )
				{
					heart_was="full";
				}
				else 
				{
					heart_was="empty";
				}

				$.post("like-button.php",  
				{
					user_id: '<?= $current_user_id ?>',
					article_id: '<?= $current_article_id ?>',
					status: heart_was
				},
				function(data, status) //upon success, 
				{

					if(data === "full")
					{
						//change the class to make it a full heart
						$("#heart").removeClass("glyphicon-heart-empty");
						$("#heart").addClass("glyphicon-heart");
					}
					if(data === "empty")
					{
						//change the class to make it an empty heart
						$("#heart").removeClass("glyphicon-heart");
						$("#heart").addClass("glyphicon-heart-empty");
					}

					if(status === "success")
					{
						button_disabled = false;
					}

				});
			}

		});
	}

	//if they're not logged in, hovering over heart will replace like count with message to log in
	if(!loggedin)
	{
		$("#like-button").hover(function()
		{
			$("#loginmessage").text("log in to like this article!");
			$("#likecount").toggle();
		},
		function()
		{
			$("#loginmessage").text("");
			$("#likecount").toggle();
		});

	}

});

// popover initialization 
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});

</script>


<?php 

//gets the usernames of all users who liked this article
try
{
	$stmt = $DB_con->prepare('SELECT user_username FROM User WHERE user_id IN (SELECT user_id FROM Article_Like_History WHERE article_id= ?)');
	$stmt -> execute([$current_article_id]);
	$usernames_array = $stmt->fetchAll();
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

$all_likers = " "; 

if(!empty($usernames_array)){
	for($i = 0; $i < count($usernames_array); $i++)
	{
		$all_likers .= $usernames_array[$i][user_username];
		
		if($i != count($usernames_array) - 1)
		{
			$all_likers .= ", ";
		}
	}
} 
else
{
	$all_likers .= "none yet!";
}

?>


<!-- LIKE BUTTON -->
<div class = "like-area" id = "like-area">
	<button type = "button" class = "btn btn-link" id ="like-button">
		<span class = "glyphicon glyphicon-heart-empty"  id = "heart"></span>
	</button>


	<span style = "font-size: 17.5px;" id = "likecount" data-toggle="popover" data-trigger="hover" title="Likers" data-content="<?= $all_likers; ?>">loading like count..</span>


	<span style = "font-size: 17.5px; color: #5DBDC5;" id = "loginmessage"></span>
</div>





<!-- build the body -->
<?php

$formatted = new_buildBody($lines, 1, count($lines)-1);

foreach($formatted as $line){
	echo "\n".$line;
}

include_once(ROOT."includes/footer.php");
?>