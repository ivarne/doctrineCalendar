<?php
if(false) {
  $em = new Doctrine\ORM\EntityManager($conn, $config, $eventManager);
}
date_default_timezone_set('Europe/Oslo');
error_reporting(E_ALL);
$microtime = microtime(true);
$debug = true;
require_once __DIR__.'/../include.php';

$spakers = array();
$types = array();
try {
  echo "\nTruncate all tables: ";
  truncateTables();
//echo "OK\nImporter Ordliste: "; importOrdliste();
  echo "OK\nImporter Talere: ";
  importTalere();
  echo "OK\nImporter Hendelse typer: ";
  importEventPre();
//echo "OK\nImporter Grupper: "; importGroups();
  echo "OK\nImporter Hendelser: ";
  importEvents();
//echo "OK\nImporterer Nyheter: "; importNews();
  echo "OK\n";
  echo "Alt som foreløpig er implementert ble importert vellykket\n";
}catch (Exception $e) {
  echo $e->getTraceAsString()."\n\n";
  echo $e->getMessage()."\n";
//throw $e;
}


/*
   * importer alle talere fra modx databasen og initialiser
*/

function importTalere() {
  global $em;
  global $spakers;
  $talere = query('SELECT taler FROM laget_modx.modx_kalender WHERE taler <> \'\' GROUP BY taler');
  
  while($taler = mysql_fetch_assoc($talere)) {
    $sfTaler = new \Entities\Speaker();
    $sfTaler->setName($taler['taler']);
    $spakers[$taler['taler']]=$sfTaler;
    $em->persist($sfTaler);
    $em->flush();
  }
  unset($talere);
}

/*
   * importerer type hendelser fra modx databasen og setter inn ansvarsområder
*/
function importEventPre() {
  global $em;
  global $types;
  $resp = new \Entities\Responsibility();
  $resp->setName( 'Ansvarlig','no');
  $resp->setDescription( 'Ansvarlig person og ansvarlig for oppryddning','no');
  $resp->setName('Responsible','en');
  $resp->setDescription( 'The person that\'s responsible for the meeting','en');
  $em->persist($resp);
  $resp = new \Entities\Responsibility();
  $resp->setName(  'Lovsangsteam','no');
  $resp->setDescription( 'Hvem er ansvarlig for lovsangsteamet','no');
  $resp->setName( 'Worship Team','en');
  $resp->setDescription( 'Who is in charge of the worship team','en');
  $em->persist($resp);
  $resp = new \Entities\Responsibility();
  $resp->setName( 'Møteleder','no');
  $resp->setDescription('Den som har ansvar for å lede møtet','no');
  $resp->setName( 'Meeting leader','en');
  $resp->setDescription( 'Person in charge of leading the meeting','en');
  $em->persist($resp);
  $resp = new \Entities\Responsibility();
  $resp->setName( 'Kjøkken','no');
  $resp->setDescription( 'Den som har ansvar for kjøkkenet','no');
  $resp->setName( 'Kitchen','en');
  $resp->setDescription( 'Person in charge of the kitchen','en');
  $em->persist($resp);
  $resp = new \Entities\Responsibility();
  $resp->setName( 'Teknikker','no');
  $resp->setDescription( 'Hvem har ansvar for teknikken','no');
  $resp->setName( 'Tech','en');
  $resp->setDescription( 'Who has the controll','en');
  $em->persist($resp);
  
  $eventTypes = query('SELECT * FROM `laget_modx`.`modx_kalender_type_hendelse`');
  while($eventType = mysql_fetch_assoc($eventTypes)) {
    $type = new \Entities\EventType();
    $type->setName($eventType['type_no'], 'no');
    $type->setName($eventType['type_en'], 'en');
    $types[$eventType['id']] = $type;
    $em->persist($type);
  }
  $em->flush();
}

function importNews() {
// den henter desverre bare norske nyheter regner med at det er viktigst
// i forhold til arkiv
  $articles = query('
       SELECT * FROM laget_modx.`modx_site_content`
       WHERE `parent` = 39
       ORDER BY laget_modx.`modx_site_content`.`createdon` ASC
       ');
  
  while($article = mysql_fetch_assoc($articles)) {
    $sfNews = new News();
    $sfNews->setTitle($article['pagetitle']);
    $sfNews->setShort($article['introtext']);
    $sfNews->setContent($article['content']);
    
    $sfNews->setDateTimeObject('created_at', new DateTime('@'.$article['createdon']));
//$sfNews->setCreated_at('@'.$article['createdon']);
    if($article['editedon']) {
      $sfNews->setDateTimeObject('updated_at', new DateTime('@'.$article['editedon']));
    }else {
      $sfNews->setDateTimeObject('updated_at', new DateTime('@'.$article['createdon']));
    }
    
    $sfNews->setCreated_by(cleanModxUserId($article['createdby']));
    if($article['editedby']) {
      $sfNews->setUpdated_by(cleanModxUserId($article['editedby']));
    }else {
      $sfNews->setUpdated_by(cleanModxUserId($article['createdby']));
    }
    $sfNews->save();
    $sfNews->free();
  }
  unset($articles);
  
  
}
/*
   * Importer arbeidsgrupper (mailinglister) fra modx databasen
*/

function importGroups() {
  $groups = query('SELECT * FROM laget_modx.`modx_webgroup_names`');
  while($group = mysql_fetch_assoc($groups)) {
    $sfGroup = new sfGuardGroup();
    $sfGroup->setName($group['name']);
    $sfGroup->set('id', $group['id']);
    $sfGroup->save();
// spar på minne i en batch importereing
    $sfGroup->free(true);
  }
  unset($groups);
  echo 'ferdig grupper, nå rettigheter';
  
  $rights = query('SELECT * FROM laget_modx.`modx_documentgroup_names`');
  while($right = mysql_fetch_assoc($rights)) {
    $sfRight = new sfGuardPermission();
    $sfRight->setName($right['name']);
    $sfRight->set('id',$right['id']);
    $sfRight->save();
    
    $sfRight->free();
  }
  unset($rights);
  
  echo 'så brukerrettigheter';
  
  
  $groupRights = query('SELECT * FROM laget_modx.`modx_webgroup_access`');
  while($groupRight = mysql_fetch_assoc($groupRights)) {
    $sfGroupRight = new sfGuardGroupPermission();
    $sfGroupRight->setPermissionId($groupRight['documentgroup']);
    $sfGroupRight->setGroupId($groupRight['webgroup']);
    $sfGroupRight->save();
    
    $sfGroupRight->free();
  }
  unset($groupRights);
}


/*
   * importerer forholdet mellom arbeidsgrupper og brukere
*/
function importGroupUsers() {
  $userGroups = query('SELECT * FROM laget_modx.`modx_web_groups`');
  
  while($userGroup = mysql_fetch_assoc($userGroups)) {
    $sfUserGroup = new sfGuardUserGroup();
    $sfUserGroup->user_id = $userGroup['webuser'];
    $sfUserGroup->group_id = $userGroup['webgroup'];
    $sfUserGroup->save();
// spar på minne
    $sfUserGroup->free(true);
  }
  unset($userGroups);
}
/*
   * importer hendelsene fra modx databasen.
*/
function importEvents() {
  $i = 0;
  global $em;
  global $spakers;
  global $types;
  $hendelser = query('SELECT * FROM laget_modx.modx_kalender ORDER BY id ASC');
  
  $resp = $em->getRepository('\Entities\Responsibility')->getResponsibilityArray('en');
  $userTable = $em->getRepository('\Entities\User');
  $eventTypeTable = $em->getRepository('\Entities\EventType');
  $SpeakerTable = $em->getRepository('\Entities\Speaker');
  while($hendelse = mysql_fetch_assoc($hendelser)) {
    $event = new \Entities\Event();
    $event->setId($hendelse['id'])
            ->setTitle($hendelse['hendelse'], 'no')
            ->setTitle($hendelse['hendelse_en'], 'en')
            ->setShort($hendelse['kort_info'], 'no')
            ->setShort($hendelse['kort_info_en'], 'en')
            ->setInfo($hendelse['info'],'no')
            ->setInfo($hendelse['hendelse_en'], 'en')
            ->setStart(new DateTime($hendelse['start']))
            ->setEnd(new DateTime($hendelse['slutt']))
            ->setIsPublic($hendelse['publisert']=='1')
            ->setVersion($hendelse['sequence'])
            ->setEdited(new \DateTime($hendelse['endret']))
            ->setCreated(new \DateTime($hendelse['opprettet']))
            ->setInternalInfo($hendelse['intern_info']);

    if($hendelse['type_hendelse']) {
      $event->setType($types[$hendelse['type_hendelse']]);
    }else {
      $event->setType($types[8]);
    }
    if(strlen($hendelse['taler']) > 6) {
      $event->setSpeaker($spakers[ $hendelse['taler'] ]);
    }
    $ans = array(
            'Responsible' => 'styreansvarlig',
            'Meeting leader' => 'leder',
            'Worship Team' => 'lovsangsteam',
            'Kitchen' => 'servering',
            'Tech' => 'tekniker'
    );
    foreach($ans as $name => $db ) {
      if($hendelse[$db]) {
        $user = $userTable->search($hendelse[$db]);
        if($user) {
          $er = new \Entities\EventResponsibility($resp[$name], $user);
        }else {
          $er = new \Entities\EventResponsibility($resp[$name], NULL, $hendelse[$db]);
        }
        $em->persist($er);
        $event->setResponsibility($er);
      }
    }
    $em->persist($event);
    if(++$i %60 == 0) {
      echo " $i ";
      //$em->flush();
    }
  }
  $em->flush();
  unset($hendelser);
}

/*
   * Importerer ordlista fra modx databasen
*/
function importOrdliste() {
  $ordliste = query('SELECT * FROM laget_modx.ordliste');
  
  
  while($ordpar = mysql_fetch_assoc($ordliste)) {
    $sfOrd = new Dictionary();
    $sfOrd->setEn($ordpar['engelsk']);
    $sfOrd->setNo($ordpar['norsk']);
    $sfOrd->save();
//spar på minne
    $sfOrd->free(true);
  }
  unset($ordliste);
}

/*
   * Funksjon for å sende spørringer til modX databasen
   *
   * @param string $sql         SQL spørringen til den modx Databasen
   * $returns resource          return typen fra mysql_query
*/
function query($sql) {
  static $conn = false;
  if(!$conn) {
// opprett en egen database tilkobling for å hente ned hendelsene fra den gamle tabellen
    $conn = mysql_connect('localhost','sfModXImport','w4DRb2NwByc6hPyr');
    if (!$conn) {
      throw new Exception('Ikke mulig å koble til database: ' . mysql_error());
    }
    //mysql_set_charset('UTF-8',$conn);
    mysql_query('SET NAMES utf8 COLLATE utf8_unicode_ci',$conn);
  }
  $ret = mysql_query($sql, $conn);
  if (!$ret) {
    throw new Exception('Ugyldig spørring: ' . mysql_error());
  }
  return $ret;
}
/*
   * Modx har to typer brukere web_users og manager_users. Alle felt som lagrer
   * brukeraktviitet bruker negative tall for web_users og positive tall for
   * manager_users.
   *
   * Denne funksjonen vil korigere for denne feilen, og hardkoder manager users
   * siden det er så få
*/
function cleanModxUserId($modxUserId) {
  if($modxUserId<0) {
    return -1 * $modxUserId;
  }
  $managerUsers = array(
          1 => 345, // admin
//2 => NULL,
          3 => 151, // ivar
          4 => 141, // joachim
          5 => 77,  // gunnar
          6 => 182, // Marius
          7 => 143, // ragnhild
          8 => 144, // ole morten
//9 => NULL,
          10=> 158 // Øyvind
  );
  return $managerUsers[$modxUserId];
}

/*
   * Funksjon for å tømme ut innholdet i en tabell før man fyller det med nytt innhold
   * @parmam strin $tabell_navn  navnet på tabellen som skal tømmes
*/
function truncateTables() {
  global $em;
  $tables = array(
          'event_responsibility',
          'eventkalender',
          'event_types',
          'responsibility',
          'speakers',
          'modx_web_user_attributes',
          'medlemskap'
  );
  foreach($tables as $table) {
    $em->getConnection()->executeQuery('TRUNCATE TABLE '.$table);

  }
  $em->getConnection()->executeQuery('INSERT INTO doctrineKalender.`modx_web_user_attributes` (SELECT * FROM laget_modx.`modx_web_user_attributes` WHERE 1)');
  $em->getConnection()->executeQuery('INSERT INTO doctrineKalender.medlemskap (SELECT * FROM laget_modx.medlemskap)');
  
}
echo "\nAntall Spørringer: ".count($logger->queries);
echo "\nMaksimlt minnebruk: ".floor(memory_get_peak_usage()/1000)/1000 .' MB';
echo "\nTidsforbruk: ".(microtime(true)-$microtime)."\n";