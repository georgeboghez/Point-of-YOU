<?php
require_once('dbconfig.php');

$conn = new mysqli(HOST, USER, PASSWORD, DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT first_name, last_name, email FROM inscriere";
$result = $conn->query($sql);

if($result->num_rows > 0) {
	echo "<br>";
	while($row = $result->fetch_assoc()){
		echo "last_name" . $row["last_name"] . " - first_name: " . $row["first_name"] . " - email: " . $row["email"] . "<br>";
	}
} else {
	echo "0 results";
}
$conn->close(); 
?>