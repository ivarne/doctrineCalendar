<?php
if(false){
  $event = new \Entities\Event(); // for at autocomplete i netbeans skal virke
  $routing = new \Laget\Routing\DummyRouting();
  $user = new \Laget\User\DummyUser();
  $date = new \DateTime();
}
$showNagigation = function() {
  ?>
<div>
  <a href="<?php echo $routing->simpleMonthView($year,$month,$onlyPublic) ?>">&lt:&lt;<?php echo __('Forrige:')?></a>
</div>
<?php
}// end $showNavigation

?>
<h2><?php echo __('Hendelser i ').strftime('%B', $date->getTimestamp())?></h2>
<?php
// view som skriver ut lista med hendelser
foreach ($events as $event):?>

<h3>
  <a href="<?php echo $routing->showEvent($event->getRawValue()) ?>"><?php echo $event->getTitle() ?></a>
</h3>
<span>
  <?php echo $event->getFullDate();?><br>
  <?php if($event->hasSpeaker()):?>
  <br><em><?php echo $event->getSpeaker()->getName()?></em>
  <?php endif;?>
  <?php if($event->hasLink()):?>
  <a href="<?php echo htmlentities($event->getLink(), ENT_QUOTES, 'UTF-8')?>">
    <?php echo htmlentities($event->getLink(), ENT_QUOTES, 'UTF-8');?>
  </a>
  <?php endif;?>
</span>
<a style="float: right" title="<?php echo __('Legg til i Google Calendar') ?>" href="<?php echo $event->getAddEventToGoogleCalendarLink($routing) ?>"><img src="http://www.google.com/calendar/images/ext/gc_button2.gif" alt="Google calendar"></a>
<div>
  <?php echo $event->getRawValue()->getFullInfo()?>
  <?php if($user->hasPermission('redigere hendelser')):?>
    <a href="<?php echo $routing->editEvent($event->getRawValue())?>">Rediger</a>
  <?php endif;?>
</div>
<?php endforeach;?>
<?php 
$year = $months[0]['y'];
?>
<table>
 <tr><td><?php echo $year ?></td>
<?php 
  foreach($months as $month){
    if($month['y'] != $year){
      $year = $month['y'];
      echo "</tr>\n <tr><td>$year</td>\n";
    }
    echo ' <td><a href="'.$routing->simpleMonthView($year, $month['m'], $onlyPublic).'">'.$month['m']."</a></td>\n  ";
  }
?>
  </tr>
</table>
