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
if(!empty($error)) {
  echo "<pre>Feil:\n";
  print_r($error);
  echo '</pre>';
}
?>

<form id="kalender_ny" action="<?php echo 1?$routing->saveEvent($event->getRawValue()):'test.php' ?>" method="post">
  <?php if($event->getId() != null):?>
    <input type="hidden" name="id" value="<?php echo $event->getId()?>">
    <input type="hidden" name="version" value="<?php echo $event->getVersion()?>">
  <?php endif;?>
  <h3><?php echo __('Offentlig informasjon')?></h3>
  <b><?php echo __('Type')?></b>
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
  <br>
  <div style="float: right">
    <a href="javascript:openpopup('/[~283~]')"><?php echo __('Ordliste popup')?></a>||
    <a href="[~181~]" target="_blank"><?php echo __('Ordliste link')?></a>
  </div>
  
  <b><?php echo __('Dato')?>:</b>
  <input name="date" id="date" type="text" size="11" value="<?php echo $event->getStart('Y-m-d') ?>">
  <br>
  <b><?php echo __('Klokkeslett')?>:</b><small>(<?php echo __('Timer:minutter')?>)</small>
  <input id="klokke" name="clock_start" value='<?php echo $event->getStart('H:i') ?>' type="text">
  <div title="<?php echo __('La stå blank om du ønsker standard varighet på 2 timer') ?>">
    <b>Varighet:</b>
    <?php echo __('Dager')?>:
    <input id="dager" name="days" value="<?php echo $event->getDays() ?>" type="text" size="1">
    <?php echo __('Slutt tid')?>:
    <input name="clock_end" value="<?php echo $event->getEnd('H:i') ?>" type="text" size="5">
  </div>

  <table>
    <tr>
      <th>&nbsp;</th><th><em><?php echo __('Norsk')?></em></th><th><em><?php echo __('Engelsk')?></em></th>
    </tr>
    <tr>
      <th><?php echo __('Tittel')?></th>
      <td><input id="hendelse" type="text" name="title_no" maxlength="70" value="<?php echo $event->getTitle('no')?>"></td>
      <td><input id="hendelse_en" type="text" name="title_en" maxlength="70" value="<?php echo $event->getTitle('en')?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Kort info')?></th>
      <td><textarea cols="30" rows="2" name="short_no"><?php echo $event->getShort('no') ?></textarea></td>
      <td><textarea cols="30" rows="2" name="short_en"><?php echo $event->getShort('en') ?></textarea></td>
    </tr>
    <tr>
      <th><?php echo __('Info')?></th>
      <td><textarea cols="38" rows="8" class="wymeditor" name="info_no"><?php echo $event->getInfo('edit','no') ?></textarea></td>
      <td><textarea cols="30" rows="8" class="wymeditor" name="info_en"><?php echo $event->getInfo('edit','en') ?></textarea></td>
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
    <tr>
      <th><?php echo __('Påmelding')?></th>
      <td>
        <select id="paamelding" name="paamelding">
          <option value="0"><?php echo __('Ingen Påmelding')?></option>
          <option value="1"><?php echo __('Enkel Påmelding')?></option>
          <option value="2"><?php echo __('Full Påmelding')?></option>
          <option value="3"><?php echo __('Gruppe påmelding')?></option>
        </select>
      </td>
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
  <input type="submit" class="wymupdate" value="<?php echo __('Lagre hendelse')?>" />
</form>


<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/wymeditor/jquery.wymeditor.js"></script>
<?php if(isset($GLOBALS['modx'])) { 
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
    $(".wymeditor").wymeditor({
      //stylesheet: 'styles.css',

      //classes panel
//      classesItems: [
//        {'name': 'date', 'title': 'PARA: Date', 'expr': 'p'},
//        {'name': 'hidden-note', 'title': 'PARA: Hidden note',
//         'expr': 'p[@class!="important"]'},
//        {'name': 'important', 'title': 'PARA: Important',
//         'expr': 'p[@class!="hidden-note"]'},
//        {'name': 'border', 'title': 'IMG: Border', 'expr': 'img'},
//        {'name': 'special', 'title': 'LIST: Special', 'expr': 'ul, ol'}
//      ],

      //we customize the XHTML structure of WYMeditor by overwriting
      //the value of boxHtml. In this example, "CONTAINERS" and
      //"CLASSES" have been moved from "wym_area_right" to "wym_area_top":
      boxHtml:   "<div class='wym_box'>"
              + "<div class='wym_area_top'>"
              + "<div class='wym_dropdown' style='float:right'>"
//              + WYMeditor.CONTAINERS
 //             + WYMeditor.CLASSES
              + "</div>"
              + WYMeditor.TOOLS
              + "</div>"
              + "<div class='wym_area_left'></div>"
              + "<div class='wym_area_right'>"
              + "</div>"
              + "<div class='wym_area_main'>"
              + WYMeditor.HTML
              + WYMeditor.IFRAME
//              + WYMeditor.STATUS
              + "</div>"
              + "<div class='wym_area_bottom'>"
              + "</div>"
              + "</div>",

      //postInit is a function called when WYMeditor instance is ready
      //wym is the WYMeditor instance
      postInit: function(wym) {

        //we make all sections in area_top render as dropdown menus:
        jQuery(wym._box)
            //first we have to select them:
            .find(".wym_area_top .wym_section")
            //then we remove the existing class which make some of them render as a panels:
//            .removeClass("wym_panel")
            //then we add the class which will make them render as a dropdown menu:
//            .addClass("wym_dropdown")
            //finally we add some css to make the dropdown menus look better:
//            .css("width", "160px")
//            .css("float", "left")
            .css("margin-right", "5px")
            .find("ul")
//            .css("width", "140px");

        //add a ">" character to the title of the new dropdown menus (visual cue)
        jQuery(this._box).find(".wym_tools, .wym_classes ")
            .find(WYMeditor.H2)
            .append("<span>&nbsp;&gt;</span>");
        
      }
      
      
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
