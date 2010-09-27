<?php
if(false){
  $event = new \Entities\Event(); // for at autocomplete i netbeans skal virke
  $routing = new \Laget\Routing\DummyRouting();
  $user = new \Laget\User\DummyUser();
}
// view som skriver ut lista med hendelser
foreach ($events as $event):?>

<h3>
  <a href="<?php echo $routing->showEvent($event) ?>"><?php echo $event->getTitle() ?></a>
</h3>
<span>
  <?php echo $event->getFullDate() ?><br>
  <?php if($event->hasSpeaker()):?>
  <br><em><?php echo $event->getSpeaker()->getName()?></em>
  <?php endif;?>
  <?php if($event->hasLink()):?>
  <a href="<?php echo htmlentities($event->getLink(), ENT_QUOTES, 'UTF-8')?>">
    <?php echo htmlentities($event->getLink(), ENT_QUOTES, 'UTF-8');?>
  </a>
  <?php endif;?>
</span>
<a title="<?php echo __('Legg til i Google Calendar') ?>" href="<?php echo $event->getAddEventToGoogleCalendarLink($routing) ?>"><img src="http://www.google.com/calendar/images/ext/gc_button2.gif" alt="Google calendar"></a>
<div>
  <?php echo $event->getFullInfo()?>
  <?php if($user->hasPermission('redigere hendelser')):?>
  <a href="<?php echo $routing->editEvent($event)?>">Rediger</a>
  <?php endif;?>
</div>
<?php endforeach;?>
