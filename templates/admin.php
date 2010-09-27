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
}

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
        <select id="event_type" onchange="setHendelsetype()" name="event_type">
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
      <th><?php echo __('Hendelse')?></th><td><em><?php echo __('Norsk')?></em></td><td><em><?php echo __('Engelsk')?></em></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input id="hendelse" type="text" name="title_no" value="<?php echo $event->getTitle('no')?>"></td>
      <td><input id="hendelse_en" type="text" name="title_en" value="<?php echo $event->getTitle('en')?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Dato')?></th>
      <td>
        <input name="date" type="text" value="<?php echo $event->getStart('Y-m-d') ?>">
        <a onClick="nwpub_cal1.popup();" onMouseover="window.status='Select date'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer;">
          <img align="absmiddle" src="manager/media/style/MODx/images/icons/cal.gif" width="16" height="16" border="0" alt="Velg dato" />
        </a>
        <script type="text/javascript" language="JavaScript" src="manager/media/script/datefunctions.js"></script>
        <script type="text/javascript">
          var elm_txt = {}; // dummy
          var pub = document.forms["kalender_ny"].elements["dato"];
          var nwpub_cal1 = new calendar1(pub,elm_txt);
          nwpub_cal1.path="[(base_url)]manager/media/";
          nwpub_cal1.year_scroll = true;
          nwpub_cal1.time_comp = false;
        </script>
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
      <th><label for="taler"><?php echo __('Taler')?></label></th>
      <td>
        <select name="speakerId">
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
    <tr>
      <th><?php echo __('Kort info')?></th><td><em><?php echo __('Norsk')?></em></td><td><em><?php echo __('Engelsk')?></em></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><textarea cols="30" rows="2" name="short_no"><?php echo $event->getShort('no') ?></textarea></td>
      <td><textarea cols="30" rows="2" name="short_en"><?php echo $event->getShort('en') ?></textarea></td>
    </tr>
    <tr>
      <th><?php echo __('Info')?></th><td><em><?php echo __('Norsk')?></em></td><td><em><?php echo __('Engelsk')?></em></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><textarea cols="30" rows="8" name="info_no"><?php echo $event->getInfo('edit','no') ?></textarea></td>
      <td><textarea cols="30" rows="8" name="info_en"><?php echo $event->getInfo('edit','en') ?></textarea></td>
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
  <?php if($event->getResponsibilities()->count()>0):?>
  <h4><?php echo __('Ansvar:') ?></h4>
  <table>
    <?php foreach ($event->getResponsibilities() as $responsibility):?>
    <tr id="responsibility<?php echo $responsibility->getId() ?>">
      <th><?php echo $responsibility->getResponsibility()->getName() ?></th>
      <td><?php echo $responsibility?></td>
      <td>
        Slett:<input name="Responsibility[<?php echo $responsibility->getId() ?>]" type="checkbox" onchange="var row = document.getElementById('responsibility'+<?php echo $responsibility->getId()?>); ((row.childNodes[5].childNodes[1].checked!=false) ? row.style.textDecoration ='line-through': row.style.textDecoration ='none')">
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
    <input type="reset" value="Ny form">
  </a>
</form>