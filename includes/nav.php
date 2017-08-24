         <!--      NAV     -->
  
  <nav class="navbar navbar-inverse" style="background: none; border: none;" id="navigationmenu" > 
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="../index.php"><span id="pewter">BAND</span>TERVIEWs</a>
          </div>
            <ul class="nav navbar-nav">


              <li><a href="../index.php"><span class="glyphicon glyphicon-home"></span></a></li>
              <li><a href="../coming-soon.php">featured</a></li>
            <!--   <li><a href="../coming-soon.php">More Interviews</a></li>
              <li><a href="../coming-soon.php">Playlists</a></li> -->
              <li><a href= "../coming-soon.php"><span class="glyphicon glyphicon-info-sign"></span></a></li>
              <li> <a href="../contact-page.php"><span class="glyphicon glyphicon-envelope"></span></a>
              </li>  
              

            </ul>



<?php
if($user->is_loggedin()==""){
              

  echo "<ul class='nav navbar-nav navbar-right'>";
  echo "<li><a href='../login.php'>login</a></li>";
  echo "<li><a href='../sign-up.php'>sign up</a></li>";
  echo "</ul>";


} else if($user->is_loggedin()!=""){

echo <<<EOT
  <ul class="nav navbar-nav navbar-right">
    <li style="margin-right: -13px;" ><a href = "../mypage.php">My Page  <sup id = "new-superscript">NEW</sup>  </a></li>
    <li><a href="../logout.php">logout <span class='glyphicon glyphicon-off' id='pewter' style='font-size: 15px'></span></a></li>
  </ul>
EOT;
}

          

?>


    </div>
  </nav> <!--      NAV     -->