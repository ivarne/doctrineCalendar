<?php
if(false){
  $speaker = new Entities\Speaker();
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\DummyRouting();
}
?>
<h2><?php echo __('Liste over de viktigste talerene i TKS (sortert på antall taler)')?></h2>

<?php foreach ($speakers as $speaker):?>
<h4>
  <a href="<?php echo '' ?>">
    <?php echo $speaker->getName()?>
  </a>
  <small>(<?php echo $speaker->getNumEvents()?>)</small>
</h4>
<p><?php echo $speaker->getAbout()?></p>
<ul>
  <?php foreach ($speaker->getEvents() as $event):?>
  <li>
    <a href="<?php echo $routing->showEvent($event)?>">
          <?php echo $event->getTitle()?>
    </a> (<?php echo trim($event->getStart('%e. %h. %Y'))?>)
    <br><?php echo $event->getShort()?>
  </li>
  <?php endforeach;?>
</ul>
<?php endforeach;?>
