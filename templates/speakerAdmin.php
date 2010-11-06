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
      <textarea name="about_no" rows="6" cols="50"><?php echo $speaker->getAbout('no')?></textarea>
    </td>
  </tr>
  <tr>
    <th><?php echo __('Engelsk Info')?></th>
    <td>
      <textarea name="about_en" rows="6" cols="50"><?php echo $speaker->getAbout('en')?></textarea>
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
  <input type="submit" value="<?php echo __('Lagre')?>">
</form>