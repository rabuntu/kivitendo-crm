<?php
	require_once("inc/stdLib.php");
	include("inc/template.inc");
	include("inc/crmLib.php");
	include("inc/FirmaLib.php");
	include("inc/LieferLib.php");
	include("inc/persLib.php");
	include("inc/UserLib.php");
	$fid=($_POST["fid"])?$_POST["fid"]:$_GET["fid"];
	$pid=($_POST["pid"])?$_POST["pid"]:$_GET["pid"];
	$id=($_POST["id"])?$_POST["id"]:$_GET["id"];
	$Q=($_GET["Q"])?$_GET["Q"]:$_POST["Q"];
	$INIT=($_POST["INIT"])?$_POST["INIT"]:$_GET["INIT"];
	if (empty($INIT)) $INIT=$id;
	$select=$_SESSION["loginCRM"];
	$selectC=(strlen($Q)==1)?$fid:$pid;
	$daten["Datum"]=date("d.m.Y");
	$daten["Zeit"]=date("H:i");
	$daten["Kontakt"]="T";
	$daten["LangTxt"]="";
	$daten["Files"]=false;
	$daten["Anzeige"]=0;
	$daten["Datei"]="";
	$daten["ODatei"]="";
	$daten["DCaption"]="";
	$daten["Q"]=$Q;
	$daten["CID"]=($pid>0)?$pid:$fid;
	$daten["Kunde"]=0;
	$daten["Anzeige"]=0;
	
	if ($_POST["verschiebe"]) {
		$rc=mvTelcall($_POST["TID"],$_POST["Anzeige"],$_POST["CID"]);
		$daten["Betreff"]=$_POST["Betreff"];
		if ($_POST["Bezug"]==$_POST["Anzeige"]) {
			$id=$_POST["TID"];
		} else {
			$id=$_POST["Bezug"];
		}											// verschiebe
	} else 	if ($_GET["hole"]) {
		$data=getCall($_GET["hole"]);
		$daten["Datum"]=$data["Datum"];
		$daten["Zeit"]=$data["Zeit"];
		$daten["Betreff"]=$data["Betreff"];
		$daten["Kontakt"]=$data["Kontakt"];
		$daten["LangTxt"]=$data["LangTxt"];
		$daten["Files"]=$data["Files"];
		$daten["Anzeige"]=$_GET["hole"];
		$select=$data["employee"];
		$daten["CID"]=$data["CID"];
		$daten["Datei"]="";
		$daten["ODatei"]=$data["Datei"];
		$daten["Kunde"]=$data["Kunde"];
		$daten["DCaption"]=$data["DCaption"];
		$id=($data["Bezug"]<>0)?$data["Bezug"]:$_GET["hole"];
		$co=getKontaktStamm($data["CID"]);
		if ($co["cp_id"]){
			$pid=$co["cp_id"];
			$fid=$co["cp_cv_id"];
		} else {
			$fid=$data["CID"];		// Einzelperson o. Firma allgem.
		}
		$selectC=$data["CID"];						// if ($_GET["hole"])
	} else if ($_POST["sichern"]) {
		$rc=insCall($_POST,$_FILES);
		if ($rc) {
			$daten["Betreff"]=$_POST["cause"];
			if ($_POST["Bezug"]=="0") {
				$id=$rc;
			} else {
				$id=$_POST["Bezug"];
			}
		} else {	
			$daten=$_POST;
			$id=$_POST["Bezug"];
		}											// if ($rc)
													// end sichern
	} else {										// default
		if ($INIT>0) {
			$data=getCall($INIT);
			$daten["Betreff"]=$data["Betreff"];
		}
	}
	switch ($Q) {
		case "C" :  $fa=getFirmaStamm($fid);
					$daten["Firma"]=$fa["name"];
					$daten["Plz"]=$fa["zipcode"];
					$daten["Ort"]=$fa["city"];
					break;
		case "V" :  $fa=getLieferStamm($fid);
					$daten["Firma"]=$fa["name"];
					$daten["Plz"]=$fa["zipcode"];
					$daten["Ort"]=$fa["city"];
					break;
		case "XC" : 
		case "CC" : 
		case "VC" : $co=getKontaktStamm($pid);
					$daten["Firma"]=$co["cp_givenname"]." ".$co["cp_name"];
					$daten["Plz"]=$co["cp_zipcode"];
					$daten["Ort"]=$co["cp_city"];
					break;
		default	  : $daten["Firma"]="xxxxxxxxxxxxxx";
	} 

	//------------------------------------------- Beginn Ausgabe
	$t = new Template($base);
	$t->set_file(array("cont" => "getCall.tpl"));
	//------------------------------------------- CRMUSER
	$t->set_block("cont","Selectbox","Block2");
	$user=getAllUser("%");
	if ($user) foreach($user as $zeile) {
		$t->set_var(array(
			Sel => ($select==$zeile["id"])?" selected":"",
			UID	=>	$zeile["id"],
			Login	=>	$zeile["login"],
		));
		$t->parse("Block2","Selectbox",true);
	}
	//------------------------------------------- Firma/Kontakte
	$t->set_block("cont","Selectbox2","Block3");
	if ($fid) $contact=getAllKontakt($fid);
	if ($fid) $first[]=array("cp_id"=>$fid,"cp_name"=>"Firma","cp_givenname"=>"allgemein");
	if ($pid) $first[]=array("cp_id"=>$pid,"cp_name"=>$co["cp_name"],"cp_givenname"=>$co["cp_givenname"]);
	$contact=array_merge($first,$contact);
	if ($contact) foreach($contact as $zeile) {
		$t->set_var(array(
			Sel => ($selectC==$zeile["cp_id"])?" selected":"",
			CID	=>	$zeile["cp_id"],
			CName	=>	$zeile["cp_name"].", ".$zeile["cp_givenname"],
		));
		$t->parse("Block3","Selectbox2",true);
	}
	//------------------------------------------- Kontaktverl�ufe
	$t->set_block("cont","Selectbox3","Block4");
	if ($Q<>"XX")	{
		$thread=getAllTelCall(($pid)?$pid:$fid,($Q=="C" || $Q=="V")); // Liste Verschieben
		$thread=array_merge(array("id"=>"0"),$thread);
	} else {
		$thread=array(array("id"=>"0"),array("id"=>"$id"));
	}
	if ($thread) foreach($thread as $zeile) {
		$t->set_var(array(
			Sel => ($id==$zeile["id"])?" selected":"",
			TID	=>	$zeile["id"],
		));
		$t->parse("Block4","Selectbox3",true);
	}
	//------------------------------------------- Kontakte
	$i=0;
	$t->set_block("cont","Liste","Block");
	$zeile="";
	if ($id<>0) {
		$calls=getAllCauseCall($id);
		if ($calls) foreach($calls as $zeile) {
			$t->set_var(array(
				LineCol => ($zeile["bezug"]==0)?$bgcol[4]:$bgcol[($i%2+1)],
				Type	=> $typcol[$zeile["kontakt"]],
				Datum	=>	db2date($zeile["calldate"]).substr($zeile["calldate"],10,6),
				Betreff	=>	$zeile["cause"],
				Kontakt	=>	$zeile["kontakt"],
				IID => $zeile["id"]
			));
			$t->parse("Block","Liste",true);
			$i++;
		}
	} ;
	//------------------------------------------- Eingabemaske
	if (empty($daten["CID"])) {
		$cid=(empty($zeile["caller_id"])?"0":$zeile["caller_id"]);
	} else {
		$cid=$daten["CID"];
	}
	$cause=(empty($daten["Betreff"]))?$zeile["cause"]:$daten["Betreff"];
	$t->set_var(array(
		INIT => $INIT,
		Person => $Person,
		Anzeige => $daten["Anzeige"],
		NBetreff => addslashes($cause),
		//NBetreff => $daten["Betreff"],
		Q => $Q,
		Firma => $daten["Firma"],
		Plz => $daten["Plz"],
		Ort => $daten["Ort"],
		NDatum => $daten["Datum"],
		NZeit => $daten["Zeit"],
		LangTxt => $daten["LangTxt"],
		CID => $cid,
		FID => $fid,
		PID => $pid,
		Bezug => ($id===0)?"0":$id,
		R1 => ($daten["Kontakt"]=="T")?" checked":"",
		R2 => ($daten["Kontakt"]=="M")?" checked":"",
		R3 => ($daten["Kontakt"]=="S")?" checked":"",
		R4 => ($daten["Kontakt"]=="P")?" checked":"",
		R5 => ($daten["Kontakt"]=="D")?" checked":"",
		R6 => ($daten["Kontakt"]=="X")?" checked":"",
		Start => $id*-1,
		Datei => $daten["Datei"],
		ODatei => (empty($daten["ODatei"]))?"":("<a href='dokumente/".$_SESSION["mansel"]."/".$daten["Kunde"]."/".$daten["ODatei"]."' target='_blank'>".$daten["ODatei"]."</a>"),
		Dcaption => $daten["DCaption"],
		ID => $id
	));
	//------------------------------------------- Dateianh�nge
 	if ($daten["Files"]){
		$t->set_block("cont","Files","Block1");
		if ($daten["Files"]) foreach($daten["Files"] as $zeile) {
			$filelink="<a href='dokumente/".$_SESSION["mansel"]."/".$zeile["kunde"]."/".$zeile["filename"]."' target='_blank'>".$zeile["filename"]."</a>";
			$t->set_var(array(
				Anhang	=> $filelink,
				DCaption => $zeile["descript"]
			));
			$t->parse("Block1","Files",true);
			$i++;
		}
	};
	$t->pparse("out",array("cont"));

?>