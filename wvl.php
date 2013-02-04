<?php
    require_once("inc/stdLib.php");
    include("inc/template.inc");
    include("inc/crmLib.php");
    $t = new Template($base);
    $t->set_file(array("wvl" => "wvl.tpl"));
    $t->set_var(array(
            ERPCSS      => $_SESSION['basepath'].'crm/css/'.$_SESSION["stylesheet"], 
            Cause => $data["Cause"],
            LangTxt => $data["LangTxt"],
            Dokument => $data["Dokument"],
            DCaption => $data["DCaption"],
            Status => $data["Status"],
            CID => $data["CID"],
            Finish => $data["Finish"],
            JQUERY        => $_SESSION['basepath'].'crm/',
            ));
    $t->set_block("wvl","Selectbox","Block1");
    $user=getAllUser("%");
    if ($user) foreach($user as $zeile) {
        $t->set_var(array(
            Sel => ($_SESSION["employee"]==$zeile["login"])?" selected":"",
            Login    =>    $zeile["login"],
        ));
        $t->parse("Block1","Selectbox",true);
    }
    $t->pparse("out",array("wvl"));
?>
