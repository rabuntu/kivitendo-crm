<!-- $Id: personen2L.tpl,v 1.2 2005/09/16 12:01:12 hli Exp $ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>
	<script language="JavaScript">
	<!--
	function showK (id,tbl) {
		{no}
		Frame=eval("parent.main_window");
		if (tbl==1) {
			uri="firma2.php?id=" + id;
		} else {
			uri="liefer2.php?id=" + id;
		}
		Frame.location.href=uri;
	}
	//-->
	</script>
<body>

<table class="reiter">
	<tr>
		<td width="25%" class="menueD reiter ce">
			<a href="personen1.php" >A . . Z Suche</a>
		</td>
		<td width="25%" class="menueA reiter ce">
			<a href="personen2.php" >erw. Suche</a>
		</td>
		<td width="25%" class="menueD reiter ce">
			<a href="personen3.php" >Edit / Neu</a>
		</td>
		<td width="25%" class="menueD reiter ce">
			<a href=""></a>
		</td>
	</tr>
</table>

<table width="99%" class="karte"><tr><td class="karte">
<!-- Hier beginnt die Karte  ------------------------------------------->
<form name="personen" action="firma2.php" method="post">
<input type="hidden" name="fid" value="{FID}">
<table class="liste">
<!-- BEGIN Liste -->
	<tr  class="smal" onMouseover="this.bgColor='#FF0000';" onMouseout="this.bgColor='{LineCol}';" bgcolor="{LineCol}" onClick="showK({PID},{TBL});"><td>{Name}</td><td>{Plz} {Ort}</td><td>{Telefon}</td><td>{eMail}</td><td>{Firma}</td><td>{insk}</td></tr>
<!-- END Liste -->	
	<tr><td colspan="5"></td><td>{snd}</td></tr>
</table>
<!-- Hier endet die Karte ------------------------------------------->
</td></tr></table>
</body>
</html>