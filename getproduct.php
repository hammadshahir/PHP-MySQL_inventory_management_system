<?php
  
  	include_once('config/connectdb.php');

	$id = $_GET["id"];

	$select = $pdo->prepare(" SELECT * FROM tbl_product WHERE pid=:ppid ");
	$select->bindParam('ppid', $id);
	$select->execute();

	$record = $select->fetch(PDO::FETCH_ASSOC);

	// print_r($record);
	

	$response = $record;

	header('Content-Type: application/json');

	echo json_encode($response);





?>