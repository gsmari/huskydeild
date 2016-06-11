<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
if(!defined('DB_HOST'))
    define('DB_HOST',getenv('OPENSHIFT_MYSQL_DB_HOST'));
if(!defined('DB_PORT'))
    define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT')); 
if(!defined('DB_USER'))
    define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
if(!defined('DB_PASS'))
    define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
class db {
    private function connect() {
        $dbh = null;
        try {
            /*** connect to SQLite database ***/
            
            $dbh = null;
            if($_SERVER['SERVER_NAME']!="localhost")
                $dsn = 'mysql:host='.DB_HOST.';dbname=pedigree;port='.DB_PORT;
            else
                $dsn = 'mysql:host=localhost;dbname=pedigree;';
            $username = '';
            $password = '';
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ); 
            if($_SERVER['SERVER_NAME']!="localhost")
                $dbh = new PDO($dsn,DB_USER,DB_PASS,$options);
            else
                $dbh = new PDO($dsn,$username,$password,$options);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
        return $dbh;
    }
    private function close($conn) {
        unset($conn);
    }
   
    
    private function insertUpdateQuery($statement,$statementvalues) {
        $conn = null;
        $success = false;
        try {
            $conn = $this->connect();
            $conn->beginTransaction();
            $dbh = $conn->prepare($statement);
            foreach ($statementvalues as $key => $value) {
                $dbh->bindValue($key, $value);
            }
            if($dbh->execute()) {
                $success = true;
            }
            else {
                $success = false;
                $error = $dbh->errorInfo();
                die($error[2]);
            }
            $conn->commit();
            $this->close($conn);
        }
        catch(PDOException $e) {
            echo $e->getMessage()."<br />";
            $success = false;
            $conn->rollback();
            $this->close($conn);
            
        }
        return $success;
    }

    private function fetchAllQuery($statement,$statementvalues=NULL) {
        $conn = null;
        $return = null;
        try {
            $conn = $this->connect();
            $dbh = $conn->prepare($statement);
            if($statementvalues!=NULL)
                foreach ($statementvalues as $key => $value) {
                    $dbh->bindValue($key, $value);
                }
            
            if($dbh->execute()) {
                if($rows = $dbh->fetchAll()) {
                    $return = $rows;
                }
            }
            else {
                $error =  $dbh->errorInfo();
                echo $error[2];
                die();
            }
            $this->close($conn);
        }
        catch(PDOException $e) {
            //echo $e->getMessage();
            $this->close($conn);
        }
        return $return;
    }

    private function fetchQuery($statement, $statementvalues=NULL, $column=NULL) {
        $conn = null;
        $return = null;
        try {
            $conn = $this->connect();
            $dbh = $conn->prepare($statement);
            if($statementvalues!=NULL)
                foreach ($statementvalues as $key => $value) {
                    $dbh->bindValue($key, $value);
                }
            if($dbh->execute()) {
                if($row = $dbh->fetch()) {
                    if(empty($column))
                        $return = $row;
                    else
                        $return = $row[$column];
                }
            }
            else {
                $error =  $dbh->errorInfo();
                echo $error[2];
                die();
            }
            $this->close($conn);
        }
        catch(PDOException $e) {
            //echo $e->getMessage();
            $this->close($conn);
        }
        return $return;
    }

    /*  
    
    public function getState($request) {
        $results = $this->fetchQuery(
            "SELECT id,name,stateorder,restriction FROM projectstates WHERE id=:id",
            array(":id" => $request['stateid']['value']));
        return $results;
    }

    */
    public function addDog($request) {
         $success = $this->insertUpdateQuery(
            "INSERT INTO dog (name,sex,dateofbirth)
            VALUES(:name,:sex,:dateofbirth)",
            array(
                ":name" => $request['name']['value'],
                ":sex" => $request['sex']['value'],
                ":dateofbirth" => $request['birthday']['value'],
            )
        );
        return $success;
    }
    public function addDogLitter($request) {
        $success = $this->addDog($request);
        if($success)
            $request['baby']['value'] = $this->getDogID($request);
        else
            return false;
        $success = $this->addPedigree($request);
        return $success;
    }
    public function getDogID($request) {
        $id = $this->fetchQuery(
            "SELECT id FROM dog WHERE name=:name",
            array(
                ":name" => $request['name']['value']),
            "id"
            );
        return $id;
    }
    public function addPedigree($request) {
        $dad = ($request['dad']['value']===-1)?null:$request['dad']['value'];
        $mom = ($request['mom']['value']===-1)?null:$request['mom']['value'];
        $success = $this->insertUpdateQuery(
            "INSERT INTO pedigree (id,sire_id,dam_id) 
            VALUES(:id,:sire,:dam)",
            array(
                ":id" => $request['baby']['value'],
                ":sire" => $dad,
                ":dam" => $mom,
            )
        );
        return $success;
    }
    public function getDogs($gender="all") {
        $results = null;
        if($gender!="all") {
            $results = $this->fetchAllQuery(
                "SELECT id,name,sex,dateofbirth FROM dog WHERE sex=:sex ORDER BY dateofbirth,id ASC",
                array(":sex" => $gender)
            );
        }
        else {
            $results = $this->fetchAllQuery(
                "SELECT id,name,sex,dateofbirth FROM dog ORDER BY dateofbirth,id ASC"
            );
        }
        return $results;
    }
    public function getPedigree() {
        $results = $this->fetchAllQuery(
            "SELECT baby.name as baby, dad.name as dad, mom.name as mom FROM pedigree p, dog baby, dog dad, dog mom WHERE baby.id = p.id AND dad.id = p.sire_id AND mom.id = p.dam_id"
        );
        return $results;
    }
    public function loginUser($username,$password) {
        if($username == "**REMOVED**" && $password == "**REMOVED**") {  //  TODO: Sleppa þessari harðkóðun
            return true;
        }
        else {
            return false;
        }

    }

}
?>
