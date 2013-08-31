<h1>Program på laget neste uke</h1>
<p>Denne uken [[bla bla bla]]</p>
<!--  Gå gjennom alle hendelser, først på norsk deretter på engelsk   -->
<?php foreach($events as $event):?>
  <h3><?php echo $event->getTittel('no')?></h3>
  <em><?php echo $event->getFullDate()?></em>
  <p><?php echo $event->getFullInfo('no')?></p>
<?php endforeach;?>

<h2>And for those who prefere English</h2>
<?php foreach($events as $event):?>
  <h3><?php echo $event->getTittel('en')?></h3>
  <em><?php echo $event->getFullDate()?></em>
  <p><?php echo $event->getFullInfo('en')?></p>
<?php endforeach;?>