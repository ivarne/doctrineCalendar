<?php
if(false){
  $speaker = new \Entities\Speaker();
  $user    = new \Laget\User\DummyUser();
  $routing = new \Laget\Routing\DummyRouting();
}
?>
<form action="<?php $routing->saveSpeaker($speaker->getRawValue())?>" >
<table>
  <tr>
    <th><?php __('Navn');?></th>
    <td><input name="name" type="text" value="<?php echo $speaker->getName() ?>"></td>
  </tr>
  <tr>
    <th><?php __('Norsk Info')?></th>
    <td>
      <textarea name="about_no" rows="6" cols="50"><?php echo $speaker->getAbout('no')?></textarea>
    </td>
  </tr>
    <tr>
    <th><?php __('Engelsk Info')?></th>
    <td>
      <textarea name="about_en" rows="6" cols="50"><?php echo $speaker->getAbout('en')?></textarea>
    </td>
  </tr>
</table>
</form>