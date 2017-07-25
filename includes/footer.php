<!-- FOOTER -->

	</div> <!-- end wrapper -->
	  <footer class="footer" style="text-align: center;">

		    <?php 
		   
			    if($user->is_loggedin()!="")
			    {
			    	echo "<p>";
			    	echo "Great to have you here, " . $_SESSION['call_them_name'].". ";
			    	echo "Have questions or want to let me know what you think? Visit the <a href='../contact-page.php' style='color: #92ADB6;'>contact page</a>!";
			    	echo "</p>";
			    } else
			      {
			    	echo "<p>";
			    	echo "<a href='../sign-up.php' style='color: #92ADB6;'>Sign up</a> or <a href='../login.php' style='color: #92ADB6;'>log in</a> to like articles!";
			    	echo "</p>";
			      }
		    ?>


	</footer>
  
  <script src= "js/script.js"></script>
  </body>
  </html>

