<?php
if(false){
  $speaker = new Entities\Speaker();
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\DummyRouting();
  $user = new \Laget\User\DummyUser();
}
?>
<h2><?php echo __('Talere i Trondheim Kristne studenlag (klikk på navnet for å få mer info)')?></h2>
<table>
  <thead>
    <tr>
      <th><?php echo __('Navn')?></th>
      <th><?php echo __('Antall taler')?></th>
      <th><?php echo __('Nyeste')?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($speakers as $speaker):?>
    <tr>
      <td><a href="<?php echo $routing->showSpeaker($speaker->getRawValue()) ?>" title="<?php echo $speaker->getAbout() ?>"><?php echo $speaker->getName()?></a></td>
      <td><?php echo $speaker->getNumEvents()?></td>
      <td>
        <?php if($speaker->getNumEvents()):?>
        <a href="<?php echo $routing->showEvent($speaker->getEvents()->first()->getRawValue()) ?>">
          <?php echo $speaker->getEvents()->first()->getStart('%b. %Y')?>
        </a>
        <?php else:?>
        -
        <?php endif;?>
      </td>
    </tr>
    <?php endforeach?>
  </tbody>
</table>
