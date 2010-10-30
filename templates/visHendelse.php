<?php
// ====================================================================================
// = Vis hendelser snippet  ===========================================================
// ==== Gitt en hendelses id vises all innfo om hendelsen til brukeren  ===============
// ------------------------------------------------------------------------------------
if(!true){
  $event = new \Entities\Event();
  $routing = new Laget\Routing\ModxRouting($lang);
  $user = new User\DummyUser();
}
?>


<h2><?php echo $event->getTitle() ?>: <?php echo $event->getStart('%R')?></h2>
<?php if($event->hasSpeaker()):?>
<div style="float:right;background-color: #EFFECC;padding: 10px; margin: 10px; border: thin solid black" class="taler">
  Taler: <em><a href="<?php echo $routing->showSpeaker($event->getSpeaker())?>"><?php echo $event->getSpeaker()->getName()?></a></em>
  <div><?php echo $event->getSpeaker()->getAbout() ?></div>
</div>
<?php endif;?>
<div style="padding: 10px; background-color: rgb(239, 254, 238);">
  <div class="dato">
    <?php echo $event->getFullDate() ?>
  </div>
  <div class="short"><?php echo $event->getShort()?></div>
</div>
<div style="width:100%;clear: both"></div>

<a href="<?php echo $event->getAddEventToGoogleCalendarLink($routing)?>" target="_blank" style="float: right;">
  <img alt="<?php echo __('Legg til i google calendar')?>" src="http://www.google.com/calendar/images/ext/gc_button2.gif" border=0>
</a>
<?php if ($event->hasInfo()):?>
<div style="padding:10px" class="long"><?php echo $event->getInfo()?></div>
<?php endif;?>

<?php if($user->isLoggedIn()):?>
<?php echo __('Du er logget inn') ?>:
<table style="margin: 1px; border: 2px dashed; -moz-border-radius: 12px 12px 12px 12px; -webkit-border-radius: 12px 12px 12px 12px;">
  <tr>
    <th colspan="2"><?php echo __('Ekstra Info') ?><th>
  </tr>
  <?php foreach ($event->getResponsibilities() as $responsibility) :?>
  <tr>
    <th title="<?php echo $responsibility->getResponsibility()->getDescription() ?>">
      <?php echo $responsibility->getResponsibility()->getName()?>
    </th>
    <td><?php echo $responsibility ?></td>
  </tr>
  <?php endforeach;?>

  <tr>
    <th><?php echo __('Publisert') ?>:</th>
    <td><?php echo $event->isPublic()? __('Ja') :__('Nei') ?></td>
  </tr>
  <?php if($event->getCreated() != $event->getEdited()):?>
  <tr>
    <th><?php echo __('Endret') ?>:</th>
    <td><?php echo $event->getEdited('%e. %h %Y %R') ?> (<?php echo $event->getVersion() .' '.__('ganger') ?>)</td>
  </tr>
  <?php endif;?>
  <tr>
    <th><?php echo __('Opprettet') ?>:</th>
    <td><?php echo $event->getCreated('%e. %h %Y %R')?></td>
  </tr>
  <tr>
    <td></td>
    <td><a href="<?php echo $routing->editEvent($event)?>"><?php echo __('Rediger')?></a></td>
  </tr>
</table>
<?php endif;?>
<?php if($concurentEvents):?>
<table>
  <tr>
    <th colspan="2"><?php echo __('Andre hendelser i samme tidsrom') ?></th>
  </tr>
  <?php foreach($concurentEvents as $event):?>
  <tr>
    <th><a href="<?php echo $routing->showEvent($event) ?>"><?php echo $event->getTitle()?></a></th>
    <td>
      <?php if($event->hasShort()):?>
      <?php echo $event->getShort()?>
      <br>
      <?php endif?>
      <?php echo $event->getFullDate()?>
    </td>
  </tr>
  <?php endforeach?>
</table>
<?php
endif;//samtidighe hendelser*/
?>

<a href="<?php echo $routing->monthView($event->getStart())?>"><?php echo __('Tilbake til kalenderen') ?></a>
