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
<?php if(!$event->hasOpenRegistration()):?>
<p><?php echo __('Påmeldingen er desverre avsluttet, ta kontakt med %ansvarlig%, og se om det er mulig å ordne noe!',array('%ansvarlig%'=>$event->getResponsibilities(\Entities\Responsibility::Ansvarlig, true)))?></p>
<?php elseif(!($user->isLoggedIn() && $event->isRegistred($user->getDoctrineUser()))):?>
<p>
<?php if($user->isLoggedIn()):?>
  <?php echo __('Du er logget inn. Hvis dataene nede er feil kan du endre dem under endre medlemsdata i menyen til høyre')?>
<?php else:?>
  <?php echo __('Du er ikke logget inn, hvis du er medlem setter vi pris på om du <a href="%a%">logger inn</a> før du melder deg på. Det er også en fin annleding til å skjekke at medlemsdataene stemmer.',array('%a%'=>$routing->login()))?>
<?php endif?>
</p>

<form action="<?php echo $routing->saveRegistration($event->getRawValue()) ?>" method="POST">
  <table>
    <tr>
      <th><?php echo __('Navn')?>:</th>
      <td><input name="name" type="text" maxlength="255" size="30" value="<?php echo $user->getName() ?>"></td>
    </tr>
    <?php if($event->hasFullRegistration()):?>
    <tr>
      <th><?php echo __('Epost')?>:</th>
      <td><input name="epost" type="email" maxlength="255" size="30" value="<?php echo $user->getEmail() ?>"></td>
    </tr>
    <tr>
      <th><?php echo __('Mobiltelefonnummer')?>:</th>
      <td><input name="tlf" type="text" maxlength="20" size="20" value="<?php echo $user->getTelephone() ?>"></td>
    </tr>
    <?php endif;?>
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
    <?php if($event->hasRegistrationTasks()):?>
    <tr>
      <td colspan="2">
        <strong><?php echo __('Velg en oppgave')?></strong>
        <?php $numTasks = 0; $numRegistrerd = 0;?>
        <ul>
        <?php foreach($event->getRegistrationTasks() as $task):?>
          <li style="padding: 5px;<?php if($task->isFull())echo 'color: gray;'?>">
            <input name="task" id="task<?php echo $task->getId() ?>" value="<?php echo $task->getId() ?>" type="radio" <?php if($task->isFull())echo 'disabled'?>>
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

<?php require 'pameldTabell.php' ?>
<br>
