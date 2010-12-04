<?php
if (!isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] != 'kammar' || $_SERVER['PHP_AUTH_PW'] != 'kammar' )) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
error_reporting(E_ALL);
date_default_timezone_set('Europe/Oslo');

$_POST = array (
  'id' => '100',
  'version' => '1',
  'event_type' => '10',
  'date' => '2008-12-01',
  'clock_start' => '18:00',
  'days' => '0',
  'clock_end' => '20:00',
  'title_no' => 'Berg-gjengen',
  'title_en' => 'Maintenance Team',
  'short_no' => 'Oppussing og vedlikehold av Berg PrestegÃ¥rd.',
  'short_en' => 'Renovation and maintenance of Berg PrestegÃ¥rd.',
  'info_no' => 'Passer for alle som har lyst Ã¥ gjÃ¸re noe mer praktisk. Berg PrestegÃ¥rd er et gammelt hus og krever dermed mye vedlikehold. Kom innom og se hva vi gjÃ¸r! Ã… arbeide med hendene er ogsÃ¥ en tjeneste i Guds rike.',
  'info_en' => '',
  'speakerId' => '',
  'newSpeaker' => '',
  'internal_info' => '',
  'isPublic' => 'on',
  'newResponsibility' =>
  array (
    5 =>
    array (
      'respId' => '1',
      'userId' => '151',
      'comment' => '',
    ),
    4 =>
    array (
      'respId' => '2',
      'userId' => '',
      'comment' => 'Ivar Nesje',
    ),
    3 =>
    array (
      'respId' => '3',
      'userId' => '',
      'comment' => 'Ivar Nesje',
    ),
    2 =>
    array (
      'respId' => '',
      'userId' => '',
      'comment' => '',
    ),
    1 =>
    array (
      'respId' => '',
      'userId' => '',
      'comment' => '',
    ),
  ),
);

// ini_set("display_errors", 1);
?><html>
  <head>
    <title>Kalender test|<?php echo $_GET['action'] ?></title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <script type="text/javascript">
    function togle(id){
      var elm = document.getElementById(id);
      if(elm.style.display == 'none'){
        elm.style.display = 'inherit';
      }else{
        elm.style.display = 'none';
      }
    }
    </script>
  </head>
  <body>
    <?php //phpinfo();?>
    <div>
      <a href="?action=SpeakerView:list">Vis Talere</a>
      <a href="?action=CalenderView:list">List Hendelser</a>
      <a href="?action=CalenderView:visHendelse&amp;event=300">Vis Hendelse</a>
    </div>
    <?php
    $microtime = microtime(true);
    
    $debug = true;
    require dirname(__FILE__).'/include.php';

    if(isset($_GET['action'])) {
      list($class,$action) = explode(':',$_GET['action']);
    }else{
      die('</body></html>');
    }
    $class = '\Laget\Controller\\'.$class.'Controller';
    $routing = new \Laget\Routing\DummyRouting();
    $user = new \Laget\User\DummyUser($em);

    //\Laget\Controller\SpeakerAdminController::$__lang;
    //echo $class::$tic;

    $kalender = new $class($em,$routing,$user);

    echo $kalender->execute($action);
    if($debug):?><br><br>
    <a onclick="togle('queries')">Queries(<?php echo count($logger->queries) ?>)</a><br>
    <a onclick="togle('server')">$_SERVER</a><br>
    <a onclick="togle('POST')">$_POST(<?php echo count($_POST)?>)</a><br>
    <div id="queries" style="display: none" >
      <h2>Query log:</h2>
      <pre><?php echo htmlentities(print_r($logger->queries, true),ENT_QUOTES,'UTF-8');?></pre>
    </div>
    <div id="server" style="display:none" >
      <h2>$_SERVER:</h2>
      <pre><?php echo htmlentities(print_r($_SERVER, true),ENT_QUOTES,'UTF-8');?></pre>
    </div>
    <div id="POST" style="display:none" >
      <h2>$_POST:</h2>
      <pre><?php echo htmlentities(print_r($_POST, true),ENT_QUOTES,'UTF-8');?></pre>
    </div>
    <pre><?php
      echo "\nMaksimlt minnebruk: ".floor(memory_get_peak_usage()/1000)/1000 .' MB';
      echo "\n\nTidsforbruk: ".(microtime(true)-$microtime);
    ?></pre>
    <?php endif;?>
  </body>
</html>