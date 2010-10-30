<?php
if(false){
  $speaker = new Entities\Speaker();
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\DummyRouting();
  $user = new \Laget\User\DummyUser();
}
?>
<h2><?php echo __('Liste over de viktigste talerene i TKS (sortert på antall taler)')?></h2>
<table>
  <thead>
    <tr>
      <th><?php echo __('Navn')?></th>
      <th><?php echo __('Antall taler')?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($speakers as $speaker):?>
    <tr>
      <td><a href="<?php echo $routing->showSpeaker($speaker->getRawValue()) ?>" title="<?php echo $speaker->getAbout() ?>"><?php echo $speaker->getName()?></a></td>
      <td><?php echo $speaker->getNumEvents()?></td>
    </tr>
    <?php endforeach?>
  </tbody>
</table>
