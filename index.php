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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Huskydeild-Gagnagrunnur</title>
	<link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <script src="bootstrap/js/bootstrap.min.js"></script>

  </head>
  <body>
    <h1>Huskydeild - Gagnagrunnur</h1>

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

    	<div class="col-md-5 add-dog">
	    	<h3>Bæta hundi við í gagnagrunn</h3>
	    	<form method="POST" name="add-dog" action=".">
				<div class="form-group">
				    <label for="name">Nafn</label>
				    <input type="text" name="name" id="name" class="form-control" placeholder="Nafn">
				</div>
			    <div class="form-group">
				    <input type="radio" name="sex" id="male" value="Male"><label for="male">Rakki</label>
				    <input type="radio" name="sex" id="female" value="Female"><label for="female">Tík</label>
				</div>
				<div class="form-group">
					<label for="birthday">Afmæli</label>
					<input type="date" name="birthday" id="birthday">
				</div>
		  		
	  			<button type="submit" class="btn btn-primary">Stofna</button>
	    	</form>
    	</div>
    	<div class="col-md-5">
    		<?php
    			$dogs = $db->getDogs();
    			if(!empty($dogs)) {
    				echo "<ul>";
    				foreach ($dogs as $value) {
    					echo "<li>".$value['name']."</li>";
    				}
    				echo "</ul>";
    			}
    			else {
    				echo "<p>Enginn hundur skráður í grunninn</p>";	
    			}

    		?>

    	</div>
    </div>

  </body>
</html>