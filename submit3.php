<?php

require_once('dbconfig.php');

$nume_reprez = $_POST['nume_reprez'] ? trim($_POST['nume_reprez']) : '';
$membrii_echipa = $_POST['membrii_echipa'] ? trim($_POST['membrii_echipa']) : '';
$email_reprez = $_POST['email_reprez'] ? trim($_POST['email_reprez']) : '';
$telefon = $_POST['telefon'] ? trim($_POST['telefon']) : '';
$titlu_proiect = $_POST['titlu_proiect'] ? trim($_POST['titlu_proiect']) : '';
$categorie = $_POST['categorie'] ? $_POST['categorie'] : '';
$descriere_proiect = $_POST['descriere_proiect'] ? trim($_POST['descriere_proiect']) : '';
$token = $_POST['token'] ? trim($_POST['token']) : '';
$errors = array();

if(empty($nume_reprez)) {
	$errors['nume_reprez'] = "Completați câmpul Nume Reprezentant";
}

if(empty($membrii_echipa)) {
	$errors['membrii_echipa'] = "Completați câmpul Membri Echipă";
}

if(empty($email_reprez)) {
	$errors['email_reprez'] = "Completați câmpul Email Reprezentant";
}

if(empty($telefon)) {
	$errors['telefon'] = "Completați câmpul Telefon Reprezentant";
}

if(empty($titlu_proiect)) {
	$errors['titlu_proiect'] = "Completați câmpul Titlu Proiect";
}

if(empty($categorie)) {
	$errors['categorie'] = "Selectați categoria din care face parte proiectul";
}

try {

	$con = new pdo('mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
} catch(Exception $e) {

	$db_error['connection'] = "Cannot connect to database";

	$response = $db_error;

	header('HTTP/1.1 503 Service Unavailable');

	echo json_encode($response);
	return;
}

if(empty($errors['nume_reprez'])) {

	if(!preg_match('/^[a-zA-ZĂăÎîÂâȘșȚț \-]*$/',$nume_reprez)) {
		$errors['nume_reprez'][] = "Numele introdus conține caractere neacceptate";
	}

	if(mb_strlen($nume_reprez) > 30 || mb_strlen($nume_reprez) < 3){
		$errors['nume_reprez'][] = "Lungimea numelui trebuie să fie cuprinsă între 3 și 30 de caractere";
	}

}

if(empty($errors['telefon'])) {
	if(!preg_match('/^[0-9+.-]*$/',$telefon)) {
		$errors['telefon'][] = "Numărul de telefon introdus este invalid";
	} else {
		$telefon = str_replace('+',"",$telefon);
		$telefon = str_replace('.',"",$telefon);
	}

	if(strlen($telefon)>13||strlen($telefon)<9) {
		$errors['telefon'][]="Lungimea numărului de telefon este necorespunzătoare";
	}
}

if(empty($errors['titlu_proiect'])) {

	if(mb_strlen($titlu_proiect) > 30 || mb_strlen($titlu_proiect) < 2){
		$errors['titlu_proiect'][] = "Lungimea titlului proiectului trebuie să fie cuprinsă între 3 și 30 de caractere";
	}

}


if(empty($errors)){
	$db_insert = $con -> prepare("UPDATE inscriere SET membrii_echipa=:membrii_echipa, nume_reprez=:nume_reprez,email_reprez=:email_reprez,telefon=:telefon,titlu_proiect=:titlu_proiect,categorie=:categorie,descriere_proiect=:descriere_proiect WHERE token=:token");
	$db_insert -> bindParam(':token', $token);
	$db_insert -> bindParam(':membrii_echipa', $membrii_echipa);
	$db_insert -> bindParam(':nume_reprez', $nume_reprez);
	$db_insert -> bindParam(':email_reprez', $email_reprez);
	$db_insert -> bindParam(':telefon', $telefon);
	$db_insert -> bindParam(':titlu_proiect', $titlu_proiect);
	$db_insert -> bindParam(':categorie', $categorie);
	$db_insert -> bindParam(':descriere_proiect', $descriere_proiect);
	
	
	if(!$db_insert->execute()){
		$errors['connection'] = "Database Error";
	}
}

print_r(json_encode($errors));
?>
