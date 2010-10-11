<?php
if(false){
  $event = new Events();
  $responsible = new EventResponsible();
}
?>
<?php
if($sf_user->isAuthenticated()){
  echo link_to(__('Rediger Hendelse'),'Events_admin/edit?id='.$event->getId());
}
?>
<h2><?php echo $event->getTitle() ?></h2>
<em><?php echo $event->getFormatedTime() ?></em><br>
<em><?php if($event->hasTaler()):?>
  <?php echo $event->getTalere()->getName()?> <br>
  <?php endif;?>
  <?php echo $event->getShort() ?>
</em>
<p><?php echo $event->getInfo() ?></p>

<h3><?php echo __('Ansvarsområder') ?></h3>
<?php foreach($event->getEventResponsible() as $responsible): ?>
<em title="<?php echo $responsible->getResponsibilities()->getDescription() ?>"><?php echo $responsible->getResponsibilities()->getName() ?></em>
<?php echo $responsible->getName()?><br>
<?php endforeach;?>
<?php
if($event->has_registration){
  if(!$sf_user->getAttribute('eventRegistred'.$event->getId(),false)){
    include_component('event_registrer','showRegistration', array('event'=> $event));
  }else{
    echo __('Du er Påmeldt denne hendelsen');
  }
  ?>
<br />
<?php
  include_component('event_registrer','showRegistrerd', array('event'=> $event));
}