<?php $i = 0?>
<ul>
<?php foreach($eventsList as $event):?>
  <li>
    <div class="event">
      <a href="<?php echo $event->getUrl() ?>">
        <span class="eventTitle"><?php echo $event->getTitle() ?></span><br />
      </a>
      <i>
        <span class="shortEventTitle"><?php echo $event->getShort() ?></span>
      </i>
    </div>
  </li>
<?php endforeach;?>
</ul>