<?php

$user_session = $_POST['usersess'];

if(!empty($user_session)){
	echo true;
}
else{
	echo false;
}

?>