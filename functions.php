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
function makeDogs($db) {
	$dogs = $db->getDogs();
	echo '<option selected value="-1">-Veldu hund-</option>';
	foreach($dogs as $dog) {
		echo '<option value="'.$dog['id'].'">'.$dog['name'].'</option>';
	}
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

?>
