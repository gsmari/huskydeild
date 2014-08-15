<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
// logout.php
require_once('includes.php');
startSession();
destroySession();
header('Location: .');
die();
?>
