<!--
Dette er grensesnittet for å redigere kalenderen

Først inkluderer vi en snippet som heter ny kalender som gjør all jobben i forhold til redigering av hendelser
og setter en haug med placeholders som parses av modx og bytter ut alt på formen [++]
-->
[!kalender!]

[+topInfo+]

<form class="noWidth" id="kalender_ny" action="[+actionURL+]" method="post">
  <input type="hidden" name="id" value="[+id+]">
	<input type='hidden' name='current' value='[+current+]'>
	<h3>Offentlig informasjon</h3>
	<table>
	<tr>
		<th>Type</th>
		<td colspan="2">
		  <select id="type_hendelse" onchange="setHendelsetype()" name="type_hendelse">[+type_hendelse_velger+]</select>
		<a href="javascript:openpopup('/[~283~]')">Ordliste popup</a>||
		<a href="[~181~]" target="_blank">Ordliste link</a></td>
	</tr>
	<tr>
		 <th>Hendelse</th><td><em>Norsk</em></td><td><em>Engelsk</em></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input id="hendelse" type="text" name="hendelse" value='[+hendelse+]'></td>
		<td><input id="hendelse_en" type="text" name="hendelse_en" value='[+hendelse_en+]'></td>
	</tr>
	<tr>
		<th>Dato</th>
		<td>
		  <input name="dato" type="text" value='[+dato+]'>
		  <a onClick="nwpub_cal1.popup();" onMouseover="window.status=\'Select date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand">
		    <img align="absmiddle" src="manager/media/style/MODx/images/icons/cal.gif" width="16" height="16" border="0" alt="Velg dato" />
		  </a>
		  <script type="text/javascript" language="JavaScript" src="manager/media/script/datefunctions.js"></script>
		  <script type="text/javascript">
			  var elm_txt = {}; // dummy
			  var pub = document.forms["kalender_ny"].elements["dato"];
			  var nwpub_cal1 = new calendar1(pub,elm_txt);
			  nwpub_cal1.path="[(base_url)]manager/media/";
			  nwpub_cal1.year_scroll = true;
			  nwpub_cal1.time_comp = false;
		  </script>
	  </td>
  </tr>
	<tr>
		<th>Klokkeslett<br><small>Timer:minutter</small></th>
		<td>
			<input id="klokke" name="klokke" value='[+klokke+]' type="text">
		</td>
		<td title="La stå blank om du ønsker standard varighet på 2 timer">
			<b>Varighet:</b><br />
			Dager:
				<input id="dager" name="dager" value='[+dager+]' type="text" size="1"><br />
			Slutt tid:
				<input name="klokke_slutt" value='[+klokke_slutt+]' type="text" size="5">
		</td>
	</tr>
	<tr>
		 <th>Taler</th><td><input id="taler" name="taler" type="text" value='[+taler+]'></td>
	</tr>
	<tr>
		 <th>Kort info</th><td><em>Norsk</em></td><td><em>Engelsk</em></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><textarea maxlength="130" cols=30 rows=2 name="kort_info">[+kort_info+]</textarea></td>
		<td><textarea maxlength="130" cols=30 rows=2 name="kort_info_en">[+kort_info_en+]</textarea></td>
	</tr>
	<tr>
		 <th>Info</th>
		 <td><em>Norsk</em></td>
		 <td><em>Engelsk</em></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><textarea cols=30 rows=6 name="info">[+info+]</textarea></td>
		<td><textarea cols=30 rows=6 name="info_en">[+info_en+]</textarea></td>
	</tr>
	<tr>
	   <th><label for="aapen_bar">Åpen Bar</label></th>
	   <td><input id="aapen_bar" name="aapen_bar" type="checkbox" [+aapen_bar+]></td><td></td>
	</tr>
	</table>
	<h3>Intern informasjon</h3>
	<table>
	<tr>
		 <th>Møteleder</th><td><input type="text" name="leder" value='[+leder+]'></td>
	</tr>
	<tr>
		 <th>Åpning</th><td><input type="text" name="aapning" value='[+aapning+]'></td>
	</tr>
	<tr>
		 <th>Tekniker</th><td><input type="text" name="tekniker" value='[+tekniker+]'></td>
	</tr>
	<tr>
		 <th>Arr.ansvarlig</th><td><input type="text" name="styreansvarlig" value='[+styreansvarlig+]'></td>
	</tr>
	<tr>
		<th>Lovsangsteam</th><td><input name="lovsangsteam" value="[+lovsangsteam+]"/></td>
	</tr>
	<tr>
		<th>Interne notat</th><td><textarea cols=30 rows=5 name="intern_info">[+intern_info+]</textarea></td>
	</tr>

	<tr>
		<th><label for="publisert">Publisert på<br /> nettsida?</label></th>
		<td><input id="publisert" name="publisert" type="checkbox" [+publisert+] ></td>
	</tr>
	<tr>
    <td>
      <input type='submit' value='Lagre hendelse' />
      <a href='[+nyFormURL+]'>
        <input type='reset' value='Ny form'>
      </a>
    </td>
  </tr>
</table>
</form>

<form action="[+sokURL+]" method="get">
  <input type="text" name="sok" value="[+sok+]"/>
	<input type="submit" value="Søk"/>
	[+soketekst+]
</form>

<a name="tabell"></a><a href="[+forigeURL+]"> [Forrige]</a> Side:[+side+]<a href="[+nesteURL+]"> [Neste] </a>
<table id="programadmin">
  <tr>
    <th>Hendelse</th>
    <th>Taler</th>
    <th>Dato</th>
    <th>Leder</th>
    <th>Åpning</th>
    <th>Tekniker</th>
    <th>Arr.ans.</th>
    <th>Lovsang</th>
    <th>Publ.</th>
    <th>Red.</th>
    <th>Slett</th>
  </tr>
  [+HendelseTabell+]
</table>
<a href="[+forigeURL+]"> [Forrige]</a> Side:[+side+]<a href="[+nesteURL+]"> [Neste] </a>
