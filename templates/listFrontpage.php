<?php
if(false){
  $event = new \Entities\Event();//autocompletion i NetBeans
}
?>
<h2><?php echo __('Program')?></h2>
<ul>
  <?php foreach ($events as $event):?>
  <li>
    <b>
      <?php echo $event->getStart('%e. %b')?>
      <a href="<?php echo $routing->showEvent($event) ?>"><?php echo $event->getTitle() ?></a>
    </b>
    <br>
    <?php if($event->hasSpeaker()):?>
    <em><?php echo $event->getSpeaker() ?></em>
    <?php endif;?>
    <?php if($event->hasShort()):?>
    <div><?php echo $event->getShort() ?></div>
    <?php endif;?>
  </li>
  <?php endforeach;?>
</ul>
