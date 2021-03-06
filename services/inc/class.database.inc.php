<?
    /**************************************************************************\    
    * This file was originaly written by Jan Dierolf (jadi75@gmx.de)           *
    * ------------------------------------------------------------------------ *
    *  This program is free software; you can redistribute it and/or modify it *
    *  under the terms of the GNU General Public License as published by the   *
    *  Free Software Foundation; either version 2 of the License, or (at your  *
    *  option) any later version.                                              *
    \**************************************************************************/
    
require_once 'DB.php';
require_once ('inc/class.datetime.inc.php');

class Database extends DB {

    function dbFehler($sql,$err) {
        $efh=fopen($this->errfile,"a");
        fputs($efh,date("Y-m-d H:i:s ->"));
        fputs($efh,$sql."\n");
        fputs($efh,$err."\n");
        fputs($efh,print_r($this->rc,true));
        fputs($efh,"\n");
        fclose($efh);
        if ($this->showErr)
            echo "</td></tr></table><font color='red'>$sql : $err</font><br>";
    }

    function showDebug($sql) {
        echo $sql."<br>";
        if ($this->debug==2) {
            echo "<pre>";
            print_r($this->rc);
            echo "</pre>";
        };
    }

    function writeLog($txt) {
        if ($this->lfh===false)
            $this->lfh=fopen($this->logfile,"a");
        fputs($this->lfh,date("Y-m-d H:i:s ->"));
        fputs($this->lfh,$txt."\n");
        fputs($this->lfh,print_r($this->rc,true));
        fputs($this->lfh,"\n");
    }

    function closeLogfile() {
        fclose($this->lfh);
    }
    
    function Database($host,$user,$pwd,$db,$port=5432,$showErr=false) {
        if ($pwd>"") {
            $passwd=$this->uudecode($pwd);
        } else {
            $passwd="";
        }
        //$dns="pgsql://$user$passwd@$host:$port/$db";
        $dsn = array(
                    'phptype'  => 'pgsql',
                    'username' => $user,
                    'password' => $passwd,
                    'hostspec' => $host,
                    'database' => $db,
                    'port'     => $port
                );
        $this->showErr=$showErr;
        $this->db=DB::connect($dsn);
        if (!$this->db || DB::isError($this->db)) {
            if ($this->log) $this->writeLog("Connect $dns");
            $this->dbFehler("Connect ".print_r($dsn,true),$this->db->getMessage()); 
            die ($this->db->getMessage());
        }
        if ($this->log) $this->writeLog("Connect: ok ");
        return $this->db;
    }

    function query($sql) {
        $this->rc=@$this->db->query($sql);
        if ($this->debug) $this->showDebug($sql);
        if ($this->log) $this->writeLog($sql);
        if(DB::isError($this->rc)) {
            $this->dbFehler($sql,$this->rc->getMessage());
            $this->rollback();
            return false;
        } else {
            return $this->rc;
        }
    }

    function begin() {
        $this->query("BEGIN");
    }
    function commit() {
        $this->query("COMMIT");
    }
    function rollback() {
        $this->query("ROLLBACK");
    }

    function getAll($sql) {
        $this->rc=$this->db->getAll($sql,DB_FETCHMODE_ASSOC);
        if ($this->debug) $this->showDebug($sql);
        if ($this->log) $this->writeLog($sql);
        if(DB::isError($this->rc)) {
            $this->dbFehler($sql,$this->rc->getMessage());
            return false;
        } else {
            return $this->rc;
        }
    }

    function saveData($txt) {
        if (get_magic_quotes_gpc()) {   
            return $txt;
        } else {
            return DB::quoteSmart($string); 
        }
    }
    
      
      static function db2time($datetime) {
        
        return DateTimeConv::timeStringToStamp($datetime);      
      }
      
      static function time2db($datetime, $dateonly) {
        
        return DateTimeConv::timeStampToString($datetime, 'sql', $dateonly);        
      }
//    
//    static function convert2db($datetimestr) {
//      return DateTimeConv::convert($datetimestr, 'sql');              
//    }

    /****************************************************
    * uudecode
    * in: string
    * out: string
    * dekodiert Perl-UU-kodierte Passwort-Strings
    * http://de3.php.net/base64_decode (bug #171)
    *****************************************************/
    function uudecode($encode) {
        $encode=stripslashes($encode);
        $b64chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        $encode = preg_replace("/^./m","",$encode);
        $encode = preg_replace("/\n/m","",$encode);
        for($i=0; $i<strlen($encode); $i++) {
            if ($encode[$i] == '') $encode[$i] = ' ';
            $encode[$i] = $b64chars[ord($encode[$i])-32];
        }
        while(strlen($encode) % 4) $encode .= "=";
        return base64_decode($encode);
    }
    
     private $db = false;
     private $rc = false;
     private $showErr = false; // Browserausgabe
     private $debug = false; // 1 = SQL-Ausgabe, 2 = zusätzlich Ergebnis
     private $log = false;  // Alle Abfragen mitloggen
     private $errfile = "lxcrm.err";
     private $logfile = "lxcrm.log";
     private $lfh = false;
     
}
?>