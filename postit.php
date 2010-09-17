<?php
// $Id$
require_once("inc/stdLib.php");
$popup=($_GET["popup"])?$_GET["popup"]:0;

function getAllPostIt($id) {
global $db;
	$sql="select * from postit where employee=$id order by date";
	$rs=$db->getAll($sql);
	return $rs;
}
function getOnePostIt($id) {
global $db;
	$sql="select * from postit where id=$id";
	$rs=$db->getAll($sql);
	if ($rs) {
		$data=$rs[0];
		$data["notes"]=stripslashes($data["notes"]);
		return $data;
	} else {
		return false;
	}
}
function savePostIt($data) {
global $db;
	if (!$data["id"]) {
		$newID=uniqid (rand());
		$rc=$db->insert(postit,array('employee','date','cause'),array($_SESSION["loginCRM"],'now()',$newID));
		if ($rc) {
                	$sql="select id from postit where cause = '$newID'";
	                $rs=$db->getAll($sql);
		} else {
			return false;
		}
                if ($rs) {
                        $data["id"]=$rs[0]["id"];
                } else {
                        return false;
                }
	}
	$rc=$db->update('postit',array('cause','notes'),array(substr($data["cause"],0,100),$data["notes"]),'id = '.$data['id']);
	return $rc;
}
function DelPostIt($id) {
global $db;
	$sql="delete from postit where id=$id";
	$rc=$db->query($sql);
	return $rc;
}
if ($_POST["save"]) {
	if ($_POST["cause"]) $rc=savePostIt($_POST);
	if (!$rc) $data=$_POST;
} else if ($_GET["hole"]) {
	$data=getOnePostIt($_GET["hole"]);
} else if ($_POST["delete"]) {
	if ($_POST["id"]) $rc=delPostIt($_POST["id"]);
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head><title><?php echo  translate(".:LxO:.","work"); ?> <?php echo  translate(".:postit:.","work"); ?></title>
    <link type="text/css" REL="stylesheet" HREF="../css/<?php echo $_SESSION["stylesheet"] ?>"></link>
    <link type="text/css" REL="stylesheet" HREF="css/<?php echo $_SESSION["stylesheet"] ?>"></link>
	<script language="JavaScript">
	<!--
	function PopUp() {
		f1=open("postit.php?popup=1","PostIt","width=600,height=400");
	}
	//-->
	</script>
	</head>
<body onLoad="if (1==<?php echo  $popup ?>) window.resizeTo(600,400);">
<p class="listtop"><?php echo  translate(".:notes:.","work"); ?></p>
<table >
<?php
$liste=getAllPostIt($_SESSION["loginCRM"]);
if ($liste) foreach($liste as $row) {
	echo "<tr class='klein'><td>";
	echo db2date(substr($row["date"],0,10))." ".substr($row["date"],11,5);
	echo "</td><td>&nbsp;[<a href='postit.php?hole=".$row["id"]."'>".$row["cause"]."</a>]</td></tr>\n";
};
?>
</table>
<form name="postit" method="post" action="postit.php">
<input type="hidden" name="id" value="<?php echo  $data["id"] ?>">
<input type="text" name="cause" size="90" maxlength="100" value="<?php echo  $data["cause"] ?>"><br />
<textarea class="normal" rows="7" cols="80" name="notes"><?php echo  $data["notes"] ?></textarea><br />
<input type="submit" class="sichern" name="save" value="<?php echo  translate(".:save:.","work"); ?>">&nbsp;
<input type="submit" class="clear" name="clear" value="<?php echo  translate(".:clear:.","work"); ?>">&nbsp;
<input type="submit" class="sichernneu" name="delete" value="<?php echo  translate(".:delete:.","work"); ?>">&nbsp;
<?php if ($_GET["popup"]==1) { ?>
<input type="button" name="ppp" value="<?php echo  translate(".:close:.","work"); ?>" onCLick="self.close();">
<?php }  else { ?>
<input type="button" name="ppp" value="<?php echo  translate(".:popup:.","work"); ?>" onCLick="PopUp();">
<?php } ?>
</from>
</body>
</html>
