<?php
if(false){
  $event = new \Entities\Event();
  $user = new \Laget\User\ModxUser();
  $routing = new Laget\Routing\DummyRouting();
  $task = new \Entities\RegistrationTask();
  $registration = new \Entities\Registration();
}
?>
<h3><?php echo __('Påmelding')?></h3>
<p>
<?php if($user->isLoggedIn()):?>
  <?php echo __('Du er logget inn. Hvis dataene nede er feil kan du endre dem under endre medlemsdata i menyen til høyre')?>
<?php else:?>
  <?php echo __('Du er ikke logget inn, hvis du er medlem setter vi pris på om du <a href="%a%">logger inn</a> før du melder deg på. Det er også en fin annleding til å skjekke at medlemsdataene stemmer.',array('%a%'=>$routing->login()))?>
<?php endif?>
</p>
<?php if(!($user->isLoggedIn() && $event->isRegistred($user->getDoctrineUser()))):?>
<form action="<?php echo $routing->saveRegistration($event->getRawValue()) ?>" method="POST">
  <table>
    <tr>
      <th><?php echo __('Navn')?>:</th>
      <td><input name="name" type="text" maxlength="255" size="30" value="<?php echo $user->getName() ?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Epost')?>:</th>
      <td><input name="epost" type="email" maxlength="255" size="30" value="<?php echo $user->getEmail() ?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Mobiltelefonnummer')?>:</th>
      <td><input name="tlf" type="text" maxlength="20" size="20" value="<?php echo $user->getTelephone() ?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Publisering')?>:</th>
      <td>
        <select name="pub" >
          <option value="0"><?php echo __('Bare administratorer')?></option>
          <option value="1" selected><?php echo __('Innloggede brukere')?></option>
          <option value="2"><?php echo __('Vis til alle at jeg er påmeldt')?></option>
        </select>
      </td>
    </tr>
    <tr>
      <th><?php echo __('Kommentar')?>:</th>
      <td>
        <textarea cols="30" rows="5" name="comment"></textarea>
      </td>
    </tr>
    <?php if(count($event->getRegistrationTasks())):?>
    <tr>
      <td colspan="2">
        <strong><?php echo __('Velg en oppgave')?></strong>
        <?php $numTasks = 0; $numRegistrerd = 0;?>
        <ul>
        <?php foreach($event->getRegistrationTasks() as $task):?>
          <li style="padding: 5px">
            <input name="task" id="task<?php echo $task->getId() ?>" value="<?php echo $task->getId() ?>" type="radio" <?php if(count($task->getRegistrations())>=$task->getNumAvailable())echo 'disabled'?>>
            <label for="task<?php echo $task->getId() ?>">
              <strong><?php echo $task->getName() ?></strong>
              (<?php echo count($task->getRegistrations()) ?>/<?php echo $task->getNumAvailable()  ?>)
              <br>
              <?php echo $task->getDescription() ?>
            </label>
          </li>
          <?php $numTasks += $task->getNumAvailable();$numRegistrerd += count($task->getRegistrations()) ?>
        <?php endforeach?>
        </ul>
        <?php echo __('Totalt %num_task% oppgaver og %num_free% ledige',array('%num_registrerd%'=>$numRegistrerd,'%num_task%'=>$numTasks,'%num_free%'=>$numTasks-$numRegistrerd))?>
      </td>
    </tr>
    <?php endif?>
  </table>
  <input type="submit" value="<?php echo __('Meld meg på!')?>">
</form>
<?php else:?>
<strong><?php echo __('Du er påmeldt, og kan ikke melde deg på igjen')?></strong>
<?php endif?>
<h3><?php echo __('Totalt er det %num% påmeldt og du kan se disse',array('%num%'=>count($event->getRegistrations())))?>:</h3>
<table>
  <thead>
    <tr>
      <th><?php echo __('Navn')?></th>
      <?php if(count($event->getRegistrationTasks())):?>
      <th><?php echo __('Gruppe')?></th>
      <?php endif?>
      <?php if($user->hasPermission('se alle påmeldte')):?>
      <th><?php echo __('Medlem')?></th>
      <th><?php echo __('Epost')?></th>
      <th><?php echo __('Telefon')?></th>
      <?php endif?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($event->getRegistrations() as $registration):?>
    <?php if(!$user->hasPermission($registration->getPublic()))continue;?>
    <tr>
      <td><?php echo $registration->getName() ?></td>
      <?php if($registration->getTask()):?>
      <td><?php echo $registration->getTask()->getName() ?></td>
      <?php endif?>
      <?php if($user->hasPermission('se alle påmeldte')):?>
      <td>
        <?php if($registration->hasUser()):?>
        <?php echo $registration->getUser()->isMember()?__('Ja'):__('Nei') ?>
        <?php else:?>
        <?php echo __('Ukjent')?>
        <?php endif?>
      </td>
      <td><?php echo $registration->getEmail() ?></td>
      <td><?php echo $registration->getTlf() ?></td>
      <?php endif?>
    </tr>
    <?php endforeach?>
  </tbody>
</table>
<?php if($user->hasPermission('se alle påmeldte')):?>
<h3><?php echo __('Epostadresser som kan puttes direkte i blindkopi feltetet om du trenger å sende mail til alle påmeldte')?>:</h3>
<?php foreach($event->getRegistrations() as $reg):?>
&quot;<?php echo $reg->getName() ?>&quot;&lt;<?php echo $reg->getEmail() ?>&gt;,
<?php endforeach?>

<h3><?php echo __('Påmeldte sortert på gruppe')?>:</h3>
<ul>
<?php $ledig = 0;?>
<?php foreach($event->getRegistrationTasks() as $task):?>
  <?php $ledig += $task->getNumAvailable() - count($task->getRegistrations());?>
  <li><strong><?php echo $task->getName() ?></strong>(<?php echo count($task->getRegistrations()).'/'.$task->getNumAvailable() ?>)<br>

  <?php foreach($task->getRegistrations() as $registration):?>
    &quot;<?php echo $registration->getName() ?>&quot;&lt;<?php echo $registration->getEmail() ?>&gt;,<br>
  <?php endforeach?>
  </li>
<?php endforeach?>
</ul>
<p><?php echo __('Det er %paa% påmeldte og %num% ledige oppgaver',array('%paa%'=>count($event->getRegistrations()),'%num%'=>$ledig))?></p>
<?php endif?>
<br>