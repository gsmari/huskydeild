<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
function __autoload($class) {
	global $path;
	if(file_exists($path.$class.'.php'))
		include $path.$class.'.php';	// path er í includes)
}
function startSession() {
    if(!isset($_SESSION)) {
	    session_name('***REMOVED***');
	    session_start();
    }
}
function destroySession() {
    if(isset($_SESSION)) {
	    session_name('***REMOVED***');
	    session_unset();
	    session_destroy();
    }
}
/*
Notkun: request_headers();
Eftir: Búið er að skila headerunum sem browserinn inniheldur.
Ætlað fyrir vefþjóna sem eru ekki apache. Ef svo er þá notum við innbyggða php breytu.
*/
function request_headers()
{
    if(function_exists("apache_request_headers")) 
    {
        if($headers = apache_request_headers()) // Apache vefþjónn
        {
            return $headers;
        }
    }
    $headers = array();
    foreach(array_keys($_SERVER) as $skey)
    {
        if(substr($skey, 0, 5) == "HTTP_")
        {
            $headername = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($skey, 0, 5)))));
            $headers[$headername] = $_SERVER[$skey];
        }
    }
    return $headers;
}
function makeDogs($db,$gender="all") {
	$dogs = $db->getDogs($gender);
	echo '<option selected value="-1">-Veldu hund-</option>';
	foreach($dogs as $dog) {
		echo '<option value="'.$dog['id'].'">'.$dog['name'].'</option>';
	}
}
function getKeys($array,$instr) {
	$keys = array();

	foreach (array_keys($array) as $key => $value) {
		if(strpos($key, $instr)!==false) {
			array_push($keys,$key);
		}
	}
	return $keys;
	
}
function validateInputLength($array) {
    $valid = true;
    $keys = array_keys($array);	// Allir lyklar í ysta fylkinu
    for($i=0;$i<sizeof($array);$i++) {
		if($array[$keys[$i]]['nullAllowed']===false && 
		!($array[$keys[$i]]['value']==="0") && 	// Leyfum gildin "0" og 0
		!($array[$keys[$i]]['value']===0) && 
		empty($array[$keys[$i]]['value'])) {
		    echo $array[$keys[$i]]['value'];
		    $valid = false;
		    break;
		}
		else if($array[$keys[$i]]['type']=='length') {
		    if(strlen($array[$keys[$i]]['value']) >= $array[$keys[$i]]['length']) {
			echo $array[$keys[$i]]['value'];
			$valid = false;
			break;
		    }
		}
		else if($array[$keys[$i]]['type']=='number') {
		    if(!empty($array[$keys[$i]]['value']) && !is_numeric($array[$keys[$i]]['value'])) {
		    	echo $array[$keys[$i]]['value'];
				$valid = false;
				break;
		    }
		    elseif(!empty($array[$keys[$i]]['validSelect']) && 
		    	$array[$keys[$i]]['validSelect'] && 
		    	$array[$keys[$i]]['value']===-1) {
		    	echo $array[$keys[$i]]['value'];
				$valid = false;
				break;	
		    }
		}
		else if($array[$keys[$i]]['type']=='date') {
		    $subject = $array[$keys[$i]]['value'];
		    $pattern = '/^([0-9]{4}-[0-9]{2}-[0-9]{2})/';	// Hex color code regex
		    if(!preg_match($pattern, $subject)) {
				echo $array[$keys[$i]]['value'];
				$valid = false;
			break;
		    }
		}
	
    }
    return $valid;
}
function filterHTML(&$value) {
	$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function removeqsvar($url, $varname) {
    return preg_replace('/([?&])'.$varname.'=[^&]+(&|$)/','$2',$url);
}
function writeHead() {
	echo '<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Huskydeild-Gagnagrunnur</title>
	<link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/default.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>';
}
function writeNavigation() {
echo '
	<li><a href="logout.php">Útskrá</a></li>
	<li><a href=".">Bæta hundi í gagnagrunn</a></li>
	<li><a href="connection.php">Bæta tengslum í gagnagrunn</a></li>
	<li><a href="litter.php">Bæta við goti</a></li>
	';
}

?>
