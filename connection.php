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
if(!empty($_REQUEST['baby'])) {
	$validate = array(
		'baby' => array(
			'value' => $_REQUEST['baby'],
			'nullAllowed' => false,
			'type' => 'number',
			'validSelect' => true),
		'dad' => array(
			'value' => $_REQUEST['dad'],
			'nullAllowed' => true,
			'type' => 'number',
			'validSelect' => false),
		'mom' => array(
			'value' => $_REQUEST['mom'],
			'nullAllowed' => true,
			'type' => 'number',
			'validSelect' => false)
		);
	if(validateInputLength($validate)) {
		if($db->addPedigree($validate)) {		// TODO: BReyta í rétt fall
			$success = true;
			$info = "Tengslum bætt við	 í gagnagrunn.";
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

    	<div class="col-md-5 add-dog">
	    	<h3>Setja inn tengsl</h3>
	    	<form method="POST" name="dog-connection" action="connection.php">
				<div class="form-group">
				    <label for="baby">Hundur</label>
				    <select name="baby" id="baby" class="form-control">
				    	<?php
			    		makeDogs($db);
				    	?>
				    </select>
				</div>
			    <div class="form-group">
				    <label for="dad">Faðir</label>
				    <select name="dad" id="dad" class="form-control">
				    	<?php
			    		makeDogs($db,"Male");
				    	?>
				    </select>
				</div>
		  		<div class="form-group">
				    <label for="mom">Móðir</label>
				    <select name="mom" id="mom" class="form-control">
				    	<?php
			    		makeDogs($db,"Female");
				    	?>
				    </select>
				</div>
	  			<button type="submit" class="btn btn-primary">Stofna</button>
	    	</form>
    	</div>
    	<div class="col-md-5">
    		<?php
    			$dogs = $db->getPedigree();
    			if(!empty($dogs)) {
    				echo "<table class='dogs'>";
    				echo "<tr>";
    					echo "<th>Hundur</th>";
    					echo "<th>Faðir</th>";
    					echo "<th>Móðir</th>";
					echo "</tr>";
    				foreach ($dogs as $value) {
    					echo "<tr>";
	    					echo "<td>".$value['baby']."</td>";
	    					if(!empty($value['dad']))
	    						echo "<td>".$value['dad']."</td>";
	    					else
	    						echo "<td></td>";
	    					if(!empty($value['mom']))
	    						echo "<td>".$value['mom']."</td>";
	    					else
	    						echo "<td></td>";
    					echo "</tr>";
    				}
    				echo "</table>";
    				
    			}
    			else {
    				echo "<p>Engin tengsl skráð í grunninn</p>";	
    			}

    		?>

    	</div>
    </div>

  </body>
</html>