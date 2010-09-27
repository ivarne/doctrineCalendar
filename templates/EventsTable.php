<?php
// vis hvilket år tabellen omfatter(datoen er i kortformat uten årstall)
$start = $eventsTable[0]->getStart('Y');
$slutt = $eventsTable[count($eventsTable)-1]->getEnd('Y');
if($start == $slutt){
  echo $start;
}else {
  echo $start . ' - ' . $slutt;
}
?>
<a name="tabell"></a>
<a href="<?php echo '$event->forigeURL' ?>"> [<?php echo __('Forrige')?>]</a>
<?php echo __('Side')?>:<?php echo '#' ?>
<a href="<?php echo '$event->nesteURL' ?>"> [<?php echo __('Neste')?>] </a>
<table id="programadmin">
  <thead>
    <tr>
      <th><?php echo __('Hendelse') ?></th>
      <th><?php echo __('Taler') ?></th>
      <th><?php echo __('Dato') ?></th>
      <th><?php echo __('Ansvarlig') ?></th>
      <th><?php echo __('Kort Info') ?></th>
      <th><?php echo __('Publ.') ?></th>
      <th><?php echo __('Red.') ?></th>
      <th><?php echo __('Slett') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i = 0; // tellevariabel for å få annenhver linje i ulik farge
      foreach ($eventsTable as $event):
    ?>
    <tr style="background-color:<?php echo (($i++%2)?'#ccddff':'#ccddee') ?>">
      <td>
        <a href="<?php echo $event->getUrl() ?>"><?php echo $event->getTittel()?></a>
      </td>
      <td><?php echo $event->hasSpeaker() ? $event->getSpeaker():'' ?></td>
      <td title="<?php echo $event->getFullDate()?>"><?php echo $event->getStart('%e. %b') ?></td>
      <td><?php echo $event->getResponsible() ?></td>
      <?php if(strlen($event->getShort())<50):?>
      <td><?php echo $event->getShort() ?></td>
      <?php else:?>
      <td><?php echo trim(substr($event->getShort(),0,47)) ?>...</td>
      <?php endif;?>
      <td>
        <a href="<?php echo $routing->publishEvent($event) ?>">
          <?php if($event->isPublic()): ?>
          <img src="" alt="<?php echo __('Upubliser') ?>">
          <?php else:?>
          <img src="" alt="<?php echo __('Publiser') ?>">
          <?php endif;?>
        </a>
      </td>
      <td><a href="<?php echo $event->getEditUrl() ?>">Rediger</a></td>
      <td><a href="<?php echo $event ?>"><?php echo __('Slett') ?></a></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<a href="<?php echo '$event->forigeURL' ?>"> [Forrige]</a> Side:<?php echo '$event->side' ?><a href="<?php echo '$event->nesteURL' ?>"> [Neste] </a>

