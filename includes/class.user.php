<?php 
class USER{

	private $db;
	function __construct($DB_con){
		$this->db = $DB_con;
	}

	public function register($username, $useremail, $userpass){
		try{
			$new_pass = password_hash($userpass, PASSWORD_DEFAULT);
			$stmt = $this->db->prepare("INSERT INTO User(user_username, user_email, user_password) VALUES(:username, :useremail, :userpass)");
		
		$stmt->bindparam(":username", $username);
		$stmt->bindparam(":useremail", $useremail);
		$stmt->bindparam("userpass", $new_pass);
		$stmt->execute();
		return $stmt;

		} catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function login($username, $userpass){
		try{
			$stmt = $this->db->prepare("SELECT * FROM User WHERE user_username=:username LIMIT 1");
			$stmt -> execute(array(':username'=>$username));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0){
				if(password_verify($userpass, $userRow['user_password'])){
					
					$_SESSION['user_session'] = $userRow['user_id'];
					$_SESSION['call_them_name'] = $userRow['user_username'];
					return true;
				}
				else{
					return false;
				}
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function is_loggedin(){
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }
 
   public function redirect($url){
       header("Location: $url");
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }

}




?>