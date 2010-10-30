<?php
/*
 * Vis redigeringsgrensesnitt for kalenderen.
 *
 * Dette er grensesnittet for å redigere kalenderen
 *
 * Først inkluderer vi en snippet som heter ny kalender som gjør all jobben i forhold til redigering av hendelser
 * og setter en haug med placeholders som parses av modx og bytter ut alt på formen <?php echo $event-> ?>
*/

if(false) {
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\DummyRouting();
  $responsibility = new \Entities\EventResponsibility($resp, $user, $comment);
  $speaker = new \Entities\Speaker();
  $user = new \Laget\User\DummyUser();
}
?>


<?php
if(!empty($error)){
  echo "<pre>Feil:\n";
  print_r($error);
  echo '</pre>';
}
?>

<form id="kalender_ny" action="<?php echo 1?$routing->saveEvent($event):'test.php' ?>" method="post">
  <?php if($event->getId() != null):?>
  <input type="hidden" name="id" value="<?php echo $event->getId()?>">
  <input type="hidden" name="version" value="<?php echo $event->getVersion()?>">
  <?php endif;?>
  <h3><?php echo __('Offentlig informasjon')?></h3>
  <table>
    <tr>
      <th><?php echo __('Type')?></th>
      <td colspan="2">
        <select id="event_type" name="event_type">
          <?php if($eventTypeId == NULL):?>
          <option value="0"></option>
          <?php endif;?>
          <?php foreach ($eventTypes as $eventType):?>
          <option <?php echo $eventType->getId() ==$eventTypeId ? 'selected="selected" ':'' ?>value="<?php echo $eventType->getId() ?>">
              <?php echo $eventType->getName()?>
          </option>
          <?php endforeach;?>
        </select>
        <a href="javascript:openpopup('/[~283~]')"><?php echo __('Ordliste popup')?></a>||
        <a href="[~181~]" target="_blank"><?php echo __('Ordliste link')?></a>
      </td>
    </tr>
    <tr>
      <th><?php echo __('Dato')?></th>
      <td>
        <input name="date" id="date" type="text" size="11" value="<?php echo $event->getStart('Y-m-d') ?>">
        <input id="textualDate" type="text" disabled size="30" style="display: none">
      </td>
    </tr>
    <tr>
      <th><?php echo __('Klokkeslett')?><br><small><?php echo __('Timer:minutter')?></small></th>
      <td>
        <input id="klokke" name="clock_start" value='<?php echo $event->getStart('H:i') ?>' type="text">
      </td>
      <td title="<?php echo __('La stå blank om du ønsker standard varighet på 2 timer') ?>">
        <b>Varighet:</b><br />
        <?php echo __('Dager')?>:
        <input id="dager" name="days" value="<?php echo $event->getDays() ?>" type="text" size="1"><br />
        <?php echo __('Slutt tid')?>:
        <input name="clock_end" value="<?php echo $event->getEnd('H:i') ?>" type="text" size="5">
      </td>
    </tr>
    <tr>
      <th>&nbsp;</th><td><em><?php echo __('Norsk')?></em></td><td><em><?php echo __('Engelsk')?></em></td>
    </tr>
    <tr>
      <td><?php echo __('Tittel')?></td>
      <td><input id="hendelse" type="text" name="title_no" value="<?php echo $event->getTitle('no')?>"></td>
      <td><input id="hendelse_en" type="text" name="title_en" value="<?php echo $event->getTitle('en')?>"></td>
    </tr>
    <tr>
      <td><?php echo __('Kort info')?></td>
      <td><textarea cols="30" rows="2" name="short_no"><?php echo $event->getShort('no') ?></textarea></td>
      <td><textarea cols="30" rows="2" name="short_en"><?php echo $event->getShort('en') ?></textarea></td>
    </tr>
    <tr>
      <td><?php echo __('Info')?></td>
      <td><textarea cols="30" rows="8" name="info_no"><?php echo $event->getInfo('edit','no') ?></textarea></td>
      <td><textarea cols="30" rows="8" name="info_en"><?php echo $event->getInfo('edit','en') ?></textarea></td>
    </tr>
    <tr>
      <th><label for="taler"><?php echo __('Taler')?></label></th>
      <td>
        <select id="speakers" name="speakerId">
          <option value=""></option>
          <?php foreach ($speakers as $speaker):?>
          <option <?php echo $speaker->getId() ==$speakerId ? 'selected="selected" ':'' ?> value="<?php echo $speaker->getId()?>">
                  <?php echo $speaker->getName() ?>
          </option>
          <?php endforeach?>
        </select>
      </td>
      <td>
        <?php echo __('Eller legg til ny taler:')?><input id="taler" name="newSpeaker" type="text">
      </td>
    </tr>
  </table>

  <h3><?php echo __('Intern informasjon')?></h3>
  <table>
    <tr>
      <th><?php echo __('Interne notat')?></th>
      <td>
        <textarea cols="30" rows="5" name="internal_info"><?php echo $event->getInternalInfo() ?></textarea>
      </td>
    </tr>
    <tr>
      <th><label for="publisert" style="width: 40px"><?php echo __('Publisert på nettsida')?>?</label></th>
      <td><input id="publisert" name="isPublic" type="checkbox"<?php echo ($event->isPublic()?'checked="checed"':'') ?> ></td>
    </tr>
  </table>
  <?php if($event->getResponsibilities()->count()==0):?>
    <h4><?php echo __('Ansvarlig')?></h4>
    <input type="hidden" name="newResponsibility[<?php echo $numNewResponsibility +1?>][respId]" value="1">
    <select name="newResponsibility[<?php echo $numNewResponsibility +1?>][userId]">
      <option value=""></option>
      <?php if(!$user->isMember()):?>
      <option value="<?php echo $user->getId()?>" selected><?php echo $user->getName()?></option>
      <?php endif?>
      <?php foreach ($members as $member):?>
      <option value="<?php echo $member->getId() ?>" <?php echo ($member->getId() == $user->getId())?'selected':'' ?>>
              <?php echo $member->getName() ?>
      </option>
      <?php endforeach;?>
    </select><br>
    <?php echo __(' Eller navn/kommentar:')?>
    <input  name="newResponsibility[<?php echo $numNewResponsibility +1?>][comment]" type="text">
  <?php else:?>
    <h4><?php echo __('Ansvar:') ?></h4>
    <table>
      <?php foreach ($event->getResponsibilities() as $responsibility):?>
      <tr id="responsibility<?php echo $responsibility->getId() ?>">
        <th><?php echo $responsibility->getResponsibility()->getName() ?></th>
        <td><?php echo $responsibility?></td>
        <td>
          Slett:<input name="Responsibility[<?php echo $responsibility->getId() ?>]" type="checkbox" onchange="deleteResponsibility(<?php echo $responsibility->getId()?>)">
        </td>
      </tr>
      <?php endforeach;?>
    </table>
  <?php endif;?>
  <h4><?php echo __('Nye Ansvarsområder')?></h4>
  <table>
    <tr>
      <th><?php echo __('Ansvar')?></th>
      <th><?php echo __('Medlem')?></th>
      <th><?php echo __('Kommentar, ev. ikke medlem ')?></th>
    </tr>
    <?php while($num = $numNewResponsibility--):?>
    <tr>
      <td>
        <select name="newResponsibility[<?php echo $num;?>][respId]">
          <option value=""></option>
            <?php foreach ($responsibilities as $responsibility):?>
          <option value="<?php echo $responsibility->getId()?>"><?php echo $responsibility->getName() ?></option>
            <?php endforeach?>
        </select>
      </td>
      <td>
        <select name="newResponsibility[<?php echo $num;?>][userId]">
          <option value=""></option>
            <?php foreach ($members as $member):?>
          <option value="<?php echo $member->getId() ?>"><?php echo $member->getName() ?></option>
            <?php endforeach;?>
        </select>
      </td>
      <td>
        <input  name="newResponsibility[<?php echo $num;?>][comment]" type="text">
      </td>
    </tr>
    <?php endwhile; ?>

  </table>
  <input type="submit" value="<?php echo __('Lagre hendelse')?>" />
  <a href="<?php echo $routing->newEvent() ?>">
    <input type="reset" value="<?php echo __('Ny hendelse')?>">
  </a>
</form>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-ui-1.8.5.custom.min.js"></script>
<?php if(isset($GLOBALS['modx'])){
  $GLOBALS['modx']->regClientCSS('assets/liksomSymfony/jsCSS/jquery-ui-1.8.5.custom.css');
}
?>
<script type="text/javascript">

$(function() {
   $( "#date" ).datepicker( {
     dateFormat:'yy-mm-dd',
     firstDay:1,
     numberOfMonths: 1
   });

});
function deleteResponsibility(id){
 var row = document.getElementById('responsibility'+id);
 var box = row.lastElementChild.lastElementChild;
 if(box.checked == true){
    row.style.color ='gray';
 }else{
   row.style.color ='black';
 }
}
</script>
