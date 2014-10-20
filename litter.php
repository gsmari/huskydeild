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
	!empty($_REQUEST['mom']) &&
	!empty($_REQUEST['dad']) &&
	!empty($_REQUEST['birthday'])) {
	$validate = array(
		'name' => array(
			'value' => $_REQUEST['name'],
			'nullAllowed' => false,
			'type' => 'length',
			'length' => 230),
		'mom' => array(
			'value' => $_REQUEST['mom'],
			'nullAllowed' => false,
			'type' => 'length',
			'length' => 230),
		'dad' => array(
			'value' => $_REQUEST['dad'],
			'nullAllowed' => false,
			'type' => 'length',
			'length' => 230),
		'birthday' => array(
			'value' => $_REQUEST['birthday'],
			'nullAllowed' => false,
			'type' => 'date'),
		);
/* TODO: Nota getKeys úr fundtions til að sækja alla keys úr $_REQUEST sem innihalda "sex-"
og hreinsa inputið úr þessum strengjum.
Einnig væri mjög æskilegt að takmarka fjölda "sex" staka í $_REQUESt fylkinu.
*/

	if(validateInputLength($validate)) {
		$success_names = array();
		$failed_names = array();
		for($i=0;$i<sizeof($_REQUEST['name']);$i++) {
			$dog = array(
				'name' => array('value' => $_REQUEST['name'][$i]),
				'sex' => array('value' => $_REQUEST['sex-'.$i]),
				'birthday' => array('value' => $_REQUEST['birthday']),
				'mom' => array('value' => $_REQUEST['mom']),
				'dad' => array('value' => $_REQUEST['dad']),
			);
			if($db->addDogLitter($dog)) {
				$success = true;
				$info = "";
				array_push($success_names, $dog['name']['value']);
			}
			else {
				$info = "";
				$success = false;
				array_push($failed_names, $dog['name']['value']);
			}
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
	    	<form method="POST" name="add-litter" action="litter.php">
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
					    <input type="radio" name="sex-0" id="male-0" value="Male"><label for="male-0">Rakki</label>
					    <input type="radio" name="sex-0" id="female-0" value="Female"><label for="female-0">Tík</label>
					</div>
					<a class="btn btn-primary" id="dogplus-0">+</a>
				</div>
				
	  			<button type="submit" class="btn btn-primary">Stofna</button>
	    	</form>
    	</div>


  </body>
</html>