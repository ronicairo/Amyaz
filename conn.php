<?php
	$conn = mysqli_connect('phpmyadmin.viaduc.fr', 'h20465', '8parDX3I', 'h20465_amyazvocab') or die(mysqli_error());
	
	if(!$conn){
		die("Error: Failed to connect to database");
	}
?>