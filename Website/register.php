<?php 
session_start();

if(isset($_POST['reg_form'])){

	if(!isset($_POST['username']){
		$_SESSION['usernameEmpty'] = "User name";
	}
}






?>