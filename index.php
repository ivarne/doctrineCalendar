<html>
  <head>
    <title>Kalender test|<?php echo $_GET['action'] ?></title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
  </head>
  <body>
    <?php //phpinfo();?>
    <div>
      <a href="?action=SpeakerView:list">Vis Talere</a>
      <a href="?action=CalenderView:list">List Hendelser</a>
      <a href="?action=CalenderView:visHendelse&amp;event=300">Vis Hendelse</a>
    </div>
    <?php

    error_reporting(E_ALL);
    $microtime = microtime(true);
    
    $debug = true;
    require dirname(__FILE__).'/include.php';

    if(isset($_GET['action'])) {
      list($class,$action) = explode(':',$_GET['action']);
    }else{
      die('</body></html>')
    }

    $class = '\Laget\Controller\\'.$class.'Controller';
    $routing = new \Laget\Routing\DummyRouting();
    $user = new \Laget\User\DummyUser();

    $kalender = new $class($em,$routing,$user);

    echo $kalender->execute($action);
    if($debug):?>
    <pre>
<?php
print_r($logger->queries);
echo "\nMaksimlt minnebruk: ".floor(memory_get_peak_usage()/1000)/1000 .' MB';
echo "\n\nTidsforbruk: ".(microtime(true)-$microtime);
?>
    </pre>
    <?php endif;?>
  </body>
</html>