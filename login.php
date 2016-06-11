<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
require_once('includes.php');
if(empty($db)) {
	$db = new db();
}
startSession();
if(!empty($_REQUEST['submit'])) {
	// Notandi hefur ekki verið auðkenndur með innanhús aðferð(NTLM)
	array_walk_recursive($_REQUEST,'filterHTML');
	$validate = array('username' => array('value' => $_REQUEST['username'],
										'length' => 250,
										'type'=>'length',
										'nullAllowed' => false),
					 'password' => array('value' => $_REQUEST['password'],
										'length' => 250,
										'type'=>'length',
										'nullAllowed' => false));
	$valid = validateInputLength($validate);
	if(!$valid) {
		$message = "Notandanafn og lykilorð má ekki vera lengra en 250 stafir.";
	}
	else {
		if($db->loginUser($_REQUEST['username'],$_REQUEST['password'])) {
			//$_SESSION["valid_user"] = $db->getUserInfoByUsername($_REQUEST['username']);
			$_SESSION["valid_user"] = "huskydeild";
			//$_SESSION["valid_name"] = $row['name'];
			$_SESSION["valid_time"] = time();
			//$_SESSION["valid_access"] = $row['accessLevel'];
			$_SESSION["valid_ip"] = getenv ( "REMOTE_ADDR" );
			session_regenerate_id(true);
			header("Location: .");
		}
		else
			$message = "Notandanafn og/eða lykilorð er ekki rétt. Vinsamlegast gakktu úr skugga um að rétt sé slegið inn.";
	}

}
?>
<html lang="is">
<head>
<!--
* Guðmundur Smári Guðmundsson
* Gudmundur.Smari.Gudmundsson@landsvirkjun.is / gummismari@gummismari.net
* S: 690-6756
* 2014
-->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
<title>Huskydeild-Gagnagrunnur</title>
<style type="text/css">
@import url("bootstrap/css/bootstrap.css") screen,print;
@import url("css/default.css") screen,print;
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="bootstrap/js/bootstrap.min.js"></script>
<body>
<div class="page-header">
	<h1>Innskráning</h1>
</div>
<div class="container">
	<?php
		if(!empty($message))
			echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$message.'</div>';
	?>
<!-- Birta relevant content hér -->
	<div id="login-form">
		<div class="input-group input-group-sm">
			<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
				<div class="form-group">
					<label for="username">Notandanafn</label>
					<input type="text" name="username" id="username" placeholder="Sláðu inn notendanafn" class="form-control" required/>    
				</div>
				<div class="form-group">
				<label for="password">Lykilorð</label>
			<input type="password" name="password" id="password" placeholder="Sláðu inn lykilorð" class="form-control" required/>
			</div>
			<input type="hidden" name="login" value="login"/>
			<input type="submit" name="submit" id="submit" value="Innskrá" class="btn btn-success"/>
			</form>
		</div>

	</div>
	
</div>
</body>
</html>
