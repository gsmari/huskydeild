<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
class db {
    private function connect() {
        $dbh = null;
        try {
            /*** connect to SQLite database ***/
            $dbh = null;
            $dsn = 'mysql:host=localhost;dbname=pedigree';
            $username = '***REMOVED***';
            $password = '***REMOVED***';
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ); 

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
    public function getDogs() {
        $results = $this->fetchAllQuery(
            "SELECT id,name,sex,dateofbirth FROM dog ORDER BY id ASC"
        );
        return $results;
    }

}
?>
