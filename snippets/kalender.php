<?php
/*
* bruk [!liksomSymfony? &action=`Class:action`!]
*/

require MODX_BASE_PATH.'assets/liksomSymfony/include.php';

list($class,$action) = explode(':',$action);


$class = '\Laget\Controller\\'.$class.'Controller';
$user = new \Laget\User\ModxUser();
$routing = new \Laget\Routing\ModxRouting($user->getLanguage());


$controller = new $class($em,$routing,$user);
echo $controller->execute($action);
?>