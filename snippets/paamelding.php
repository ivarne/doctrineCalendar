<?php
/*
 * Snippet for å lage en enkel påmelding til arrangementer
 *   Bruk [!paameld? &event=`Navnlos2010`!] altså navnet på hendelsen (uten æøå) og årstallet
 *
 * - Lager ny hendelse
 * - Fyller ut ferdig infromasjon for brukere som er pålogget
 */
if(!isset($event)){
  return '<strong>Påmelding kan ikke vises da snippeten er kalt feil uten spesifisert event.</strong>';
}

if($modx->runSnippet('getLanguage') == 'no'){
  $l = array(
    'tlf' => 'Telefonnummer',
    'epost' => 'Epost adresse',
    'navn'  => 'Navn',
    'kommentar' => 'Kommentar (allergi og lignende)',
    'errNavn'=> 'Navnet er ikke godkjennt',
    'errTlf' => 'Telefonnummer er ikke godkjennt',
    'errEpost' => 'Epostadressen er ikke godkjennt',
    'pameldEpost' => 'Påmelding %event%',
    'pameldSubj' => "Hei %navn%,\n Du er nå påmeldt Bli kjent helg med laget 3.-5. september vi gleder oss til å se deg og bli bedre kjent"
  );
}else{
  $l = array(
    'tlf' => 'Telephone',
    'epost' => 'Email',
    'navn'  => 'Name',
    'kommentar' => 'Comment (allergy ...)',
    'errNavn'=> 'The name is not valid',
    'errTlf' => 'Telephone number is not valid',
    'errEpost' => 'Email address is not valid',
    'pameldEpost' => 'Registration for %event%',
    'pameldSubj' => "Hi %navn%,\n You are now registrerd for the Intro Weekend at vassfjellet 3.-5. of september. We are looking forward to get to know you better.",
  );
}
if(!function_exists('esc')){
  function esc($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}
$user = $modx->userLoggedIn();
$brukerGruppe = $modx->getUserDocGroups($user['id']);
if($user){
  $user = mysql_fetch_assoc($modx->db->query('SELECT * FROM modx_web_user_attributes WHERE internalKey = '.$user['id']));
}else{
  $user = array(
    'id'=>null,
    'fullname'=>null,
    'email'=>null,
    'mobilephone'=>null
  );
}

if(isset($_POST['paameld'])){
  // valider påmelding
  $error = array();
  if(strlen($_POST['navn'])>100 || strlen($_POST['navn'])<5 ){
    $error[] = $l['errNavn'];
  }
  if(!filter_var($_POST['epost'], FILTER_VALIDATE_EMAIL)){
    $error[] = $l['errEpost'];
  }
  if(!is_numeric($_POST['tlf'])){
    $error[] = $l['errTlf'];
  }
  if(empty($error)){
    $sql = 'INSERT INTO `laget_modx`.`paamelding` (`event` ,`navn` ,`epost` ,`tlf` ,`tid` ,`kommentar`)'
          ."VALUES ( '$event', '".$modx->db->escape($_POST['navn'])."', '".$modx->db->escape($_POST['epost'])."', '".$modx->db->escape($_POST['tlf'])."', NOW( ) , '".$modx->db->escape($_POST['kommentar'])."')";
    if($modx->db->query($sql)){
      mail(
              $_POST['epost'],
              strtr($l['pameldSubj'],array('%event%' => $event)),
              strtr($l['pameldEpost'],array('%event%'=> $event,'%navn%'=>$_POST['navn'])),
              "From: Laget-web <web@laget.net>\r\nReply-To: Joachim Salmonsen <joachim.salomonsen@gmail.com>"
      );
    }
  }
}

$paameldinger = $modx->db->query('SELECT * FROM paamelding WHERE event = \''.$modx->db->escape($event).'\'');
$antall_paameldt = mysql_num_rows($paameldinger);
$modx->setPlaceholder('antall_paameldt',$antall_paameldt);
?>

<?php
if(!empty($error)):?>
<div style="background-color: yellow"><ul>
  <?php foreach ($error as $err):?>
  <li><?php echo $err?></li>
  <?php endforeach;?>
  </ul></div>
<?php endif; ?>
<form action="<?php echo esc($_SERVER['REQUEST_URI']) ?>" method="post">
  <input name="event" type="hidden" value="<?php echo esc($event) ?>">
  <label for="navn"><?php echo $l['navn'] ?>:</label>
  <input name="navn" id="navn" type="text" value="<?php echo esc($user['fullname']) ?>" ><br>
  <label for="epost"><?php echo $l['epost'] ?>:</label>
  <input name="epost" id="epost" type="text" value="<?php echo esc($user['email']) ?>" ><br>
  <label for="tlf"><?php echo $l['tlf'] ?></label>
  <input name="tlf" id="tlf" type="text" value="<?php echo esc($user['mobilephone']) ?>"><br>
  <label for="kommentar"><?php echo $l['kommentar'] ?></label>
  <textarea name="kommentar" id="kommentar" rows="4" cols="40"><?php echo esc($user['kommentar']) ?></textarea><br>
  <input type="submit" name="paameld" value="Meld meg på!">
</form>

<?php if($user['id']):?>
  <?php if($antall_paameldt != 0):?>
    <table>
    <?php while($paa = mysql_fetch_assoc($paameldinger)):?>
      <?php
      if (!in_array("programbehandling",$brukerGruppe)){
        continue;
      }?>
      <tr>
        <td><?php echo esc($paa['navn']) ?></td>
        <td>&lt;<?php echo esc($paa['epost']) ?>&gt;,</td>
      </tr>
    <?php endwhile;?>
    </table>
  <?php endif; ?>
<?php endif; ?>