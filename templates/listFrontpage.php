<?php
if(false){
  $event = new \Entities\Event();//autocompletion i NetBeans
}
?>
<ul>
  <?php foreach ($events as $event):?>
  <li>
    <b>
      <?php echo $event->getStart('Ymd') == date('Ymd')?$event->getStart('%R'):$event->getStart('%e. %b')?>
      <a href="<?php echo $routing->showEvent($event->getRawValue()) ?>"><?php echo $event->getTitle() ?></a>
    </b>
    <br>
    <?php if($event->hasSpeaker()):?>
    <em><?php echo $event->getSpeaker() ?></em>
    <?php endif;?>
    <?php if($event->hasShort() && $event->hasSpeaker()):?>
    <br>
    <?php endif?>
    <?php if($event->hasShort()):?>
    <?php echo $event->getShort() ?>
    <?php endif;?>
  </li>
  <?php endforeach;?>
</ul>

