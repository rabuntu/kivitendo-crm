<?php
    require_once("inc/stdLib.php");
    include("inc/template.inc");
    include("inc/persLib.php");
    
    if ($_POST["ok"]) {
        $fid=updDocFld($_POST);
        $docid=$_POST["docid"];
    } else if ($_POST["neu"]) {
        $fid=insDocFld($_POST);
        $docid=$_POST["docid"];
    }  else if ($_POST["del"]) {
        $fid=delDocFld($_POST);
        $docid=$_POST["docid"];
    } else {
        $docid=($_GET["docid"])?$_GET["docid"]:$_POST["docid"];
    }
    $link1="dokument1.php";
    $link2="dokument2.php?did=$docid";
    $link3="dokument3.php?docid=$docid";
    $link4="";
    $doc=getDocVorlage($docid);
    $t = new Template($base);
    $menu =  $_SESSION['menu'];
    $t->set_var(array(
        JAVASCRIPTS   => $menu['javascripts'],
        STYLESHEETS   => $menu['stylesheets'],
        PRE_CONTENT   => $menu['pre_content'],
        START_CONTENT => $menu['start_content'],
        END_CONTENT   => $menu['end_content'],
        'THEME'         => $_SESSION['theme'],
    ));

    $t->set_file(array("doc" => "dokument3.tpl"));
    $t->set_var(array(
        ERPCSS => $_SESSION['basepath'].'crm/css/'.$_SESSION["stylesheet"],
        Link1 => $link1,
        Link2 => $link2,
        Link3 => $link3,
        Link4 => $link4,
        vorlage => $doc["document"]["vorlage"]
        ));
    $t->set_block("doc","Liste","Block");
    if ($doc["felder"]) {
        foreach($doc["felder"] as $zeile) {
            $t->set_var(array(
                feldname_    => $zeile["feldname"],
                platzhalter_ =>    $zeile["platzhalter"],
                laenge_ =>    $zeile["laenge"],
                zeichen_    =>    $zeile["zeichen"],
                position_ =>    $zeile["position"],
                beschreibung_ =>    $zeile["beschreibung"],
                docid =>    $zeile["docid"],
                fid =>    $zeile["fid"],
            ));
            $t->parse("Block","Liste",true);
        }
    } else {
        $t->set_var(array(
            Block => "",
            docid => $docid
        ));
    }
    $t->pparse("out",array("doc"));
?>
