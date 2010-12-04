<?php
if(false){
  $speaker = new \Entities\Speaker();
  $user    = new \Laget\User\DummyUser();
  $routing = new \Laget\Routing\DummyRouting();
}
?>
<form action="<?php echo $routing->saveSpeaker($speaker->getRawValue())?>" method="post">
<table>
  <tr>
    <th><?php echo __('Navn');?></th>
    <td><input name="name" type="text" value="<?php echo $speaker->getName() ?>"></td>
  </tr>
  <tr>
    <th><?php echo __('Norsk Info')?></th>
    <td>
      <textarea class="wymeditor" name="about_no" rows="6" cols="50"><?php echo $speaker->getAbout('no')?></textarea>
    </td>
  </tr>
  <tr>
    <th><?php echo __('Engelsk Info')?></th>
    <td>
      <textarea class="wymeditor" name="about_en" rows="6" cols="50"><?php echo $speaker->getAbout('en')?></textarea>
    </td>
  </tr>
  <tr>
    <th><?php echo __('Telefonnummer')?></th>
    <td><input name="tlf" value="<?php echo $speaker->getTelephone()?>" type="text"></td>
  </tr>
  <tr>
    <th><?php echo __('Epost')?></th>
    <td><input name="email" value="<?php echo $speaker->getEmail()?>" type="text"></td>
  </tr>
</table>
  <input type="hidden" name="speakerId" value="<?php echo $speaker->getId()?>">
  <input type="hidden" name="version" value="<?php echo $speaker->getVersion()?>">
  <input type="submit" class="wymupdate" value="<?php echo __('Lagre')?>">
</form>

<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/wymeditor/jquery.wymeditor.js"></script>

<script type="text/javascript">

  $(function() {
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
              + WYMeditor.CONTAINERS
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
              + WYMeditor.STATUS
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
</script>