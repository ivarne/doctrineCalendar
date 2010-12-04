<?php
if(false){
  $event = new \Entities\Event();
  $user = new Laget\User\DummyUser();
  $routing = new Laget\Routing\DummyRouting();
}
/*
 * Template som skriver ut hendelsene
 * slik de vises på facebook siden vis programmet
 */

?>
<ul>
  <?php foreach ($events as $event):?>
  <li>
    <big>
      <a href="http://laget.net<?php echo $routing->showEvent($event->getRawValue())?>"><?php echo $event->getTitle() ?></a>
    </big>
    <b><?php echo $event->getFullDate() ?></b>
    <br>
    <?php if($event->hasSpeaker()):?>
    Taler: <i><?php echo $event->getSpeaker()->getName() ?></i>
    <?php endif?>
    <p><?php echo $event->getFullInfo('esc_raw')?></p>
  </li>
  <?php endforeach?>
</ul>