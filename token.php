<?php

require_once('dbconfig.php');

$errors = array();
$token = $_POST['token'] ? trim($_POST['token']) : '';

try {

	$con = new pdo('mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
} catch(Exception $e) {

	$db_error['connection'] = "Cannot connect to database";

	$response = $db_error;

	header('HTTP/1.1 503 Service Unavailable');

	echo json_encode($response);
	return;
}

$get_token = $con ->prepare("SELECT token,membrii_echipa, nume_reprez, email_reprez, telefon, titlu_proiect, categorie, descriere_proiect FROM inscriere where token=:token");
$get_token -> bindParam(":token",$token);
if(!$get_token -> execute()){
			$errors['connection'] = $get_token->errorInfo();
			print_r(json_encode($errors));
		}else{
			$user_id = $get_token->fetch(PDO::FETCH_ASSOC);
			if($user_id['token'])
				print_r(json_encode($user_id));
			else
				{
					$errors['token']="Token invalid";
					print_r(json_encode($errors));
				}
		}

?>