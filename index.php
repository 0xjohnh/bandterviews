<?php 
session_start();
$title="Bandterviews Homepage";
include_once("config.php"); // this is for defining the site root
include('includes/header.php'); 
include('includes/article-build-functions.php');
?>
     
      




<div class="wrapper" >             
  <div class="jumbotron" id="jumbotron" style="color: #1A1B25; text-align: center;"> <!--   JUMBOTRON -->
    <h1>WELCOME TO <span id="pewter">BAND</span>TERVIEWs<?php 
      if($user->is_loggedin()!=""){
         echo ", ".$_SESSION['call_them_name'];
      } 
    ?>!
    </h1>
      <p>An interview-style blog spotlighting musicians, their music, and all the stories they'd like to share. The site was created to be informational and inspiring for music-lovers and musicians alike. Hopefully you'll find the content riveting! Visit the contact page to get in touch with me if you'd like to talk.</p>
  </div>  
          



<?php 

 //SQL QUERY to get the MORE ARTICLES section data 
          $stmt = $DB_con->prepare("SELECT*FROM Article WHERE article_isfeature = 1 AND article_type = 'interview' LIMIT 1");
          $stmt->execute();
          $feat_interview = $stmt->fetchAll();

          $stmt = $DB_con->prepare("SELECT*FROM Article WHERE article_isfeature = 1 AND article_type = 'article' LIMIT 1");
          $stmt->execute();
          $feat_article = $stmt->fetchAll();

          $link1 = make_article_link($feat_interview[0][article_id], "interview");
          $link2 = make_article_link($feat_article[0][article_id], 'article');

?>


  <div class = "container-fluid" style = "padding-left: 4%; padding-right: 4%;">
    <div class = "row">


      <div class = "col col-sm-8">  <!-- COLUMN 1 -->
        <h2 style=" text-decoration: underline;">L A T E S T</h2>


        <a href = <?= $link1 ?> > 
          <div class = "imageContainer center-block" style="position: relative; max-width: 100%">
            <img src="images/lost-son-photo.jpg" alt="Lost Son" class="centerBlock" style="max-height:100%; max-width:100%; margin-bottom: 0px; position: relative; filter: grayscale(0);   border-radius: 6px; ">
            <div class="topleft center-block"> 
              <h2><span id="pewter"><?= $feat_interview[0][article_title] ?></span></h2>
            </div>
            <p class="description"><?= $feat_interview[0][article_description] ?></p>
          </div> 
        </a>

        <a href = <?= $link2 ?> >
         <div class = "col-sm-12  bgrd" id="article" style="max-width: 100%;" >
            <h2 class=""><span id="pewter"><?= $feat_article[0][article_title] ?></span></h2>
            <p class= "description"><?= $feat_article[0][article_description] ?></p>
          </div>
        </a>


      </div>


      <div class = "col col-sm-4">  <!-- COLUMN 2 -->
        <h2 style="text-decoration: underline;">M O R E</h1> 
          
          <div class = "bgrd">
          <?php 



          //SQL QUERY to get the MORE ARTICLES section data 
          $stmt = $DB_con->prepare("SELECT*FROM Article WHERE article_isfeature != 1 ORDER BY article_id ASC LIMIT 6");
          $stmt->execute();
          $more_articles = $stmt->fetchAll();


          //Loops through the returned array from SQL query and outputs the "More Articles" section composed of 6 articles
          $i = 5;
          while($i>0)
          {

            echo "\n<div class='row'>";

              $final_link = make_article_link($more_articles[$i][article_id], $more_articles[$i][article_type]);
              echo "\n\t<a href = '{$final_link}'>";
                echo "\n\t<div class='col-sm-12' style = ' background-color: #C1EBEC; margin-bottom: 50px; border-radius: 5px;'>";
                 
                  echo "\n\t\t<h2> {$more_articles[$i][article_title]} </h2>";
                  echo "\n\t\t<p id = 'date' >Date:" . " " . format_date( $more_articles[$i][article_date] ) . "</p>";
                  echo "\n\t\t<p>" . $more_articles[$i][article_description] . "</p>";

                echo "\n</div>";
              echo "\n</a>";

            echo "\n</div>";

                $i = $i - 1;
          }
          ?>

          </div>
      </div>


    </div>
  </div>




<?php include('includes/footer.php'); ?>
