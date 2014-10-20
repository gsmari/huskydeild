<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
/* Includes á allar síður */
include('includes.php');
if(empty($db)) {
	$db = new db();
}
startSession();
require_once($path.'checkLogin.php');
$success = null;
$info = "";
if(!empty($_REQUEST['name']) && 
	!empty($_REQUEST['sex'])) {
	$validate = array(
		'name' => array(
			'value' => $_REQUEST['name'],
			'nullAllowed' => false,
			'type' => 'length',
			'length' => 230),
		'sex' => array(
			'value' => $_REQUEST['sex'],
			'nullAllowed' => false,
			'type' => 'length',
			'length' => 20),
		'birthday' => array(
			'value' => $_REQUEST['birthday'],
			'nullAllowed' => true,
			'type' => 'date'),
		);
	if(validateInputLength($validate)) {
		if($db->addDog($validate)) {
			$success = true;
			$info = $validate['name']['value']." hefur verið bætt við í gagnagrunn.";
		}
		else {
			$info = "Eitthvað fór úrskeiðis, engu var bætt við í gagnagrunn.";
			$success = false;
		}
	}
}

?>

<!DOCTYPE html>
<html lang="is">
  <head>
	<?php
		writeHead();
	?>
  </head>
  <body>
    <h1><a href=".">Huskydeild - Gagnagrunnur</a></h1>
	<ul class="nav nav-pills">
		<?=writeNavigation();?>
	</ul>
    <div class="row">
    	<?php
    	if($success!=null) {
    		if($success) {
    		?>
    			<div class="alert alert-success" role="alert"><?=$info?></div>
    	<?php
			}
			else {
				?>
				<div class="alert alert-danger" role="alert"><?=$info?></div>	

		<?php
			}
		}
    	?>

    	<div class="col-md-4 add-dog">
	    	<h3>Bæta við goti í gagnagrunn</h3>
	    	<form method="POST" name="add-litter" action=".">
				<div class="form-group">
					<label for="birthday">Fæðingardagur</label>
					<input type="date" name="birthday" id="birthday">
					<p>Í Firefox: yyyy-mm-dd</p>
				</div>
				<div class="parents-parent">
					<div class="form-group parents">
					    <label for="dad">Faðir</label>
					    <select name="dad" id="dad" class="form-control">
					    	<?php
				    		makeDogs($db,"Male");
					    	?>
					    </select>
					    
					</div>
			  		<div class="form-group parents">
					    <label for="mom">Móðir</label>
					    <select name="mom" id="mom" class="form-control">
					    	<?php
				    		makeDogs($db,"Female");
					    	?>
					    </select>
					</div>
				</div>
				<div class="dog-name-container">
					<div class="form-group dog-name">
					    <label for="name">Nafn</label>
					    <input type="text" name="name[]" class="form-control" placeholder="Nafn">
					</div>
				    <div class="form-group dog-name sex">
					    <input type="radio" name="sex[]" value="Male"><label for="male">Rakki</label>
					    <input type="radio" name="sex[]" value="Female"><label for="female">Tík</label>
					</div>
					<a class="btn btn-primary" id="dogplus-0">+</a>
				</div>
				
	  			<button type="submit" class="btn btn-primary">Stofna</button>
	    	</form>
    	</div>


  </body>
</html>