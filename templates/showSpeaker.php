<?php
if(false){
  $speaker = new \Entities\Speaker();
  $user    = new \Laget\User\DummyUser();
  $routing = new \Laget\Routing\DummyRouting();
  $event   = new \Entities\Event();
}
?>
<h2><?php echo $speaker->getName()?></h2>
<small><?php echo __('Er registrert på %num% taler', array('%num%'=>$speaker->getNumEvents()))?></small>
<div><?php echo $speaker->getRawValue()->getAbout()?></div>

<?php if($user->isLoggedIn()):?>
<table style="padding-top: 20px">
  <tr>
    <th><?php echo __('Epost')?>:</th>
    <td><?php echo $speaker->getEmail()?></td>
  </tr>
  <tr>
    <th><?php echo __('Telefonnummer')?>:</th>
    <td><?php echo $speaker->getTelephone()?></td>
  </tr>
</table>
<a href="<?php echo $routing->editSpeaker($speaker->getRawValue())?>"><?php echo __('Rediger talerinfo')?></a>
<?php endif?>


<?php if(!$speaker->getEvents()->isEmpty()):?>
<h3><?php echo $year = $speaker->getEvents()->first()->getStart('Y')?></h3>
<ol>
<?php $i = 1;?>
<?php foreach ($speaker->getEvents() as $event):?>
  <?php if($event->getStart('Y') != $year):?>
    <?php $year = $event->getStart('Y')?>
    </ol>
    <h3><?php echo $year?></h3>
    <ol>
  <?php endif?>
  <li value="<?php echo $i++ ?>" title="<?php echo $event->getInfo(false) ?>">
    <a href="<?php echo $routing->showEvent($event->getRawValue())?>"><?php echo $event->getTitle()?></a> (<?php echo utf8_encode($event->getStart('%A %e %B %Y')) ?>)<br>
    <?php echo $event->getShort()?>
  </li>
 <?php endforeach;?>
</ol>
<?php endif;//hasEvents?>
