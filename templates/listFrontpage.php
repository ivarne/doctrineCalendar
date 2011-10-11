<?php
if(false){
  $event = new \Entities\Event();//autocompletion i NetBeans
  $resp = new \Entities\EventResponsibility();
}
$t = false;
?>
<ul>
  <?php foreach ($events as $event):?>
  <li>
    <b>
      <a href="<?php echo $routing->showEvent($event->getRawValue()) ?>">
              <?php echo $event->getTitle() ?>
      </a>
      <br>
      <?php echo $event->getStart('Ymd') == date('Ymd')?__('I dag ').$event->getStart('%R'):ucfirst($event->getStart('%A %e. %b '))?>
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
<?php if($userResponsibilities):?>
<h3><?php echo __('De neste hendelsene du har ansvar for:')?></h3>
<ul>
  <?php foreach($userResponsibilities as $resp):
    $event = $resp->getEvent()?>
  <li>
    <span title="<?php echo $resp->getResponsibility()->getDescription() ?>"<b><?php echo $resp->getResponsibility()->getName() ?></b></span><br>
    <?php echo $event->getStart('Ymd') == date('Ymd')?$event->getStart('%R'):$event->getStart('%e. %b')?>
    <a href="<?php echo $routing->showEvent($event->getRawValue()) ?>">
            <?php echo $event->getTitle() ?>
    </a>
    <br>
    <?php if($event->hasSpeaker()):?>
      <em><?php echo $event->getSpeaker() ?></em>
    <?php endif;?>
    <?php if($event->hasShort() && $event->hasSpeaker()):?>
      <br>
    <?php endif;?>
    <?php if($event->hasShort()):?>
      <?php echo $event->getShort() ?>
    <?php endif;?>
  </li>
  <?php endforeach;?>
</ul>
<?php endif;
