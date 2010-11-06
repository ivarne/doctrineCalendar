<?php
if(false){
  $routing = new Laget\Routing\DummyRouting();
  $event = new \Entities\Event();
}
?>
<h2><?php echo __('Slett Hendelse:')?></h2>
<?php include 'visHendelse.php';?>
<form action="<?php echo $routing->deleteEvent($event->getRawValue())?>" method="post">
  <input name="event" value="<?php echo $event->getId() ?>" type="hidden">
  <label for="bekreftelse"><?php echo __('Vil du virkelig slette hendelsen?') ?></label>
  <input id ="bekreftelse" name="bekreft_sletting" type="checkbox">
  <input type="submit" value="Slett">
</form>
