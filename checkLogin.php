<?php
/*
* Guðmundur Smári Guðmundsson
* gummismari@gummismari.net / Gudmundur.Smari.Gudmundsson@landsvirkjun.is
* S: 690-6756
* 2014
*/

if(empty($_SESSION["valid_user"])) {
	header("Location: login.php");
	die();
}	
?>
