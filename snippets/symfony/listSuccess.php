<?php
/*
 * var Events $events
 */
?>
<h1>Kalender Hendelser</h1>
<ul>
  <?php foreach($events as $event){?>
  <li>
    <?php echo $event->getTitle()?>
  </li>
  <?php }?>
</ul>
