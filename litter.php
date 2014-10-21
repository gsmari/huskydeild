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
if(!empty($_REQUEST['mom']) &&
	!empty($_REQUEST['dad']) &&
	!empty($_REQUEST['birthday'])) {

	$sex = getKeys($_REQUEST,"sex-");
	$names = getKeys($_REQUEST,"name-");
	if(sizeof($sex)!=sizeof($names)) {
		$success = false;
		$info = "Tilgreina þarf nafn og kyn á öllum hundum.";
	}
	elseif(sizeof($names)>20) {
		$success = false;
		$info = "Ekki er hægt að skrá fleiri en 20 hunda í einu.";
	}
	$validate = array(
		'name' => array(
			'value' => $names,
			'nullAllowed' => false,
			'type' => 'array',
			'underType' => 'length',
			'strLength' => 230,
			'sizeof' => 20),
		'sex' => array(
			'value' => $sex,
			'nullAllowed' => false,
			'type' => 'array',
			'underType' => 'gender',
			'sizeof' => 20),
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

	if(!$success && validateInputLength($validate)) {
		$progress_names = array();
		$keys_names = array_keys($validate['name']['value']);
		$keys_sex = array_keys($validate['sex']['value']);
		for($i=0;$i<sizeof($keys_names);$i++) {
			$dog = array(
				'name' => array('value' => $validate['name']['value'][$keys_names[$i]]),
				'sex' => array('value' => $validate['sex']['value'][$keys_sex[$i]]),
				'birthday' => array('value' => $_REQUEST['birthday']),
				'mom' => array('value' => $_REQUEST['mom']),
				'dad' => array('value' => $_REQUEST['dad']),
			);
			if($db->addDogLitter($dog)) {
				array_push($progress_names, array('value' => $dog['name']['value'],
					'success' => true));
			}
			else {
				array_push($progress_names, array('value' => $dog['name']['value'],
					'success' => false));
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
    	<div class="col-md-10 add-dog">
    	<?php
    	for($i=0;$i<sizeof($progress_names);$i++) {
    		if($progress_names[$i]['success']) {
    			$info = $progress_names[$i]['value']." var bætt við í grunninn.";
    		?>
    			<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span>
						<?=$info?>
					</span>
    			</div>
    	<?php
			}
			else {
				$info = $progress_names[$i]['value']." var EKKI bætt við í grunninn.";
				?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span>
						<?=$info?>
					</span>
				</div>
		<?php
			}
		}
    	?>
		</div>
	</div>
	<div class="row">
    	<div class="col-md-4 add-dog">
	    	<h3>Bæta við goti í gagnagrunn</h3>
	    	<form method="POST" name="add-litter" action="litter.php">
				<div class="form-group">
					<label for="birthday">Fæðingardagur</label>
					<input type="date" name="birthday" id="birthday" required>
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
					    <input type="text" name="name-0" class="form-control" placeholder="Nafn">
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