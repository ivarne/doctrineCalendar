<?php
if(false){
  $event = new \Entities\Event();
  $user = new Laget\User\DummyUser();
  $routing = new \Laget\Routing\DummyRouting();
  $registration = new \Entities\Registration();
}
global $modx;
if (isset($modx) && $modx instanceof \DocumentParser){
$modx->regClientCSS('assets/liksomSymfony/jsCSS/jquery.tablesorter/themes/blue/style.css');
}
?>
<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery.tablesorter/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
); 
    
</script>

<?php if($event->hasPayment() && $user->hasPermission('update_registration_paymens')):?>
<form action="<?php echo $routing->updateRegistrationPaymentInfo($event->getRawValue()) ?>" method="POST">
<?php endif?>
<table id="myTable" class="tablesorter">
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
      <th><?php echo __('Kommentar')?></th>
      <?php if($event->hasPayment()):?>
      <th><?php echo __('Betalt') ?></th>
      <?php endif // hasPayment?>
      <?php endif // se alle påmeldte?>
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
        <input 
            type="checkbox" 
            name="faik[<?php echo $registration->getId() ?>]" 
            <?php echo $registration->isFaikMember()?'checked="checked"':'' ?>
            title="Juksemedlem EG. medlem i KRIK på 17. mai fest, eller andre grunner til at medlemspris er gjeldende"
            <?php if(!$user->hasPermission('update_registration_paymens')):?>
            disabled="disabled"
            <?php endif;?>>
      </td>
      <td><?php echo $registration->getEmail() ?></td>
      <td><?php echo $registration->getTlf() ?></td>
      <td><?php echo $registration->getComment() ?:'&nbsp;'?></td>
      <?php if($event->hasPayment()):?>
      <td>
        <?php if($user->hasPermission('update_registration_paymens') && !$registration->isPaymentOk()):?>
          <input type="text" name="payment[<?php echo $registration->getId() ?>]" value="<?php echo $registration->getPayedAmount() ?>" size="4">
        <?php else:?>
          <span style="color: <?php echo $registration->isPaymentOk()?'green':'red' ?>"><?php echo (int)$registration->getPayedAmount() ?></span>
        <?php endif?>
      </td>
      <?php endif?>
      <?php endif // $event->hasPayment()?>
    </tr>
    <?php endforeach?>
  </tbody>
</table>
<?php if($event->hasPayment() && $user->hasPermission('se alle påmeldte')):?>
<p>
  <?php echo __('Totalt skal de %totReg% påmeldte betale %total% kr. Foreløpig har %betalt% folk betalt %forelopig% kroner og de resterende %ikke_betalt% skal betale %mangler% kroner',
          array(
            '%total%'=>    $event->getTotalIncome(),
            '%forelopig%'=>$event->getTotalPayment(),
            '%mangler%'=>  $event->getTotalIncome() - $event->getTotalPayment(),
            '%totReg%' =>  count($event->getRegistrations()),
            '%betalt%' =>  $event->getNumPayments(),
            '%ikke_betalt%'=> count($event->getRegistrations()) - $event->getNumPayments()
          ))?>
</p>
<table>
  <tr>
    <th>Pris</th>
    <th>Antall betalt</th>
    <th>Sum betalt</th>
    <th>Antall forventet</th>
    <td>Sum forventet</td>
  </tr>
  <?php foreach($event->getPaymentDistribution() as $price => $num):?>
  <tr>
    <td><?php echo $price ?></td>
    <td><?php echo $num[0] ?></td>
    <td><?php echo $num[0]*$price ?></td>
    <td><?php echo $num[1] ?></td>
    <td><?php echo $num[1]*$price ?></td>
  </tr>
  <?php endforeach;?>
  <tr>
    <td></td>
    <td></td>
    <th><?php echo $event->getTotalPayment().'/'.$event->getTotalIncome() ?></th>
  </tr>
</table>
<?php if($user->hasPermission('update_registration_paymens')):?>
<input type="submit" value="<?php echo __('Oppdter betalingsstatus')?>">
</form>
<?php endif;?>
<?php if($nonPayed = $event->getNonPayedRegistration()):?>
  <h3><?php echo __('Disse påmeldte har ikke betalt:');?></h3>
  <?php foreach($nonPayed as $registration):?>
    &quot;<?php echo $registration->getName() ?>&quot; &lt;<?php echo $registration->getEmail() ?>&gt;,<br>
  <?php endforeach?>
  <?php foreach($nonPayed as $registration):?>
    <?php echo $registration->getTlf() ?>;
  <?php endforeach?>
<?php endif?>
<?php if($errorPayed = $event->getErrorPayedRegistrations()):?>
  <h3><?php echo __('Disse påmeldte har tilsynelatende betalt feil beløp:');?></h3>
  <?php foreach($errorPayed as $registration):?>
    &quot;<?php echo $registration->getName() ?>&quot; &lt;<?php echo $registration->getEmail() ?>&gt;,<br>
  <?php endforeach?>
<?php endif?>
<?php endif?>
<?php if($user->hasPermission('se alle påmeldte')):?>
<h3><?php echo __('Påmeldte sortert på gruppe')?>:</h3>
<ul>
<?php $ledig = 0;?>
<?php foreach($event->getRegistrationTasks() as $task):?>
  <?php $ledig += $task->getNumAvailable() - count($task->getRegistrations());?>
  <li><strong><?php echo $task->getName() ?></strong>(<?php echo count($task->getRegistrations()).'/'.$task->getNumAvailable() ?>)<br>

  <?php foreach($task->getRegistrations() as $registration):?>
    &quot;<?php echo $registration->getName() ?>&quot; &lt;<?php echo $registration->getEmail() ?>&gt;,<br>
  <?php endforeach?>
  <?php foreach($task->getRegistrations() as $registration):?>
    <?php echo $registration->getTlf() ?>;
  <?php endforeach?><br>
  </li>
<?php endforeach?>
</ul>
<p><?php echo __('Det er %paa% påmeldte og %num% ledige oppgaver',array('%paa%'=>count($event->getRegistrations()),'%num%'=>$ledig))?></p>
<?php endif?>