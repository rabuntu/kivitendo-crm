<!-- $Id$ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>
	<link type="text/css" REL="stylesheet" HREF="css/tabcontent.css"></link>
	<script language="JavaScript">
	<!--
	function showP (id,nr) {
		if (id!='') {
			Frame=eval("parent.main_window");
			f1=open("rechng.php?id="+id+"&nr="+nr,"rechng","width=700,height=420,left=10,top=10,scrollbars=yes");
		}
	}
	//-->
	</script>
<body>
<p class="listtop">Detailansicht {FAART}</p>
<div style="position:absolute; top:2.7em; left:1.2em;  width:42em;">
	<ul id="maintab" class="shadetabs">
	<li><a href="{Link1}">Kundendaten</a><li>
	<li><a href="{Link2}">Ansprechpartner</a></li>
	<li class="selected"><a href="{Link3}" id="aktuell">Ums&auml;tze</a></li>
	<li><a href="{Link4}">Dokumente</a></li>
	</ul>
</div>

<span style="position:absolute; left:1em; top:4.3em; width:99%;">
<!-- Hier beginnt die Karte  ------------------------------------------->
<div style="position:absolute; left:0px; top:0em; width:35em; border:1px solid black">
	<span class="fett">{Name} &nbsp; {kdnr}</span><br />
	{Plz} {Ort}
</div>
<span style="position:absolute; left:38em; top:0.7em;">[<a href="opportunity.php?fid={FID}">Auftragschancen</a>]</span>
<div style="position:absolute; left:1em; top:5em; width:45em;text-align:center;" class="normal">
Ums&auml;tze/Angebote von Monat {Monat}
	<table width="100%">
		<tr>
			<th class="klein" style="width:6em">Datum</th>
			<th class="klein">Nummer</th>
			<th class="klein">Netto</th>
			<th class="klein">Brutto</th>
			<th class="klein" width="4em"></th>
			<th class="klein">Art</th>
			<th class="klein">OP</th>
		</tr>
<!-- BEGIN Liste -->
		<tr onMouseover="this.bgColor='#FF0000';" onMouseout="this.bgColor='{LineCol}';" bgcolor="{LineCol}" onClick="showP('{Typ}{RNid}','{RNr}');">
			<td class="klein">{Datum}</td>
			<td class="klein ce">&nbsp;{RNr}&nbsp;</td>
			<td class="klein re">{RSumme}&nbsp;&nbsp;</td>
			<td class="klein re">{RBrutto}&nbsp;</td>
			<td class="klein">{Curr}</td>
			<td class="klein ce">&nbsp;{Typ}</td>
			<td class="klein ce">&nbsp;{offen}</td>
		</tr>
<!-- END Liste -->
		<tr><td class="klein" colspan="6"><b>R</b>echnung, <b>A</b>ngebot, <b>L</b>ieferung/Auftrag</td></tr>
		<tr><td class="klein" colspan="6"><b>o</b>ffen, <b>c</b>losed, <b>+</b>bezahlt, <b>-</b>unbezahlt</td></tr>
	</table>
</div>	
<!-- Hier endet die Karte ------------------------------------------->
</span>
</body>
</html>
