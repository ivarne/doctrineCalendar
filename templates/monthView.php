<?php
if(false){
  $user = new Laget\User\ModxUser();
}
global $modx;
if (isset($modx) && $modx instanceof \DocumentParser){
//$modx->regClientScript('assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js');
//$modx->regClientScript('assets/liksomSymfony/jsCSS/ui.core.js');
//$modx->regClientScript('assets/liksomSymfony/jsCSS/ui.draggable.js');
//$modx->regClientScript('assets/liksomSymfony/jsCSSy/ui.resizable.js');
//$modx->regClientStartupScript('assets/liksomSymfony/jsCSS/fullcalendar.min.js');
$modx->regClientCSS('assets/liksomSymfony/jsCSS/fullcalendar.css');
}
?>
	<script type="text/javascript" src="assets/liksomSymfony/jsCSS/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="assets/liksomSymfony/jsCSS/ui.core.js"></script>
	<script type="text/javascript" src="assets/liksomSymfony/jsCSS/ui.draggable.js"></script>
	<script type="text/javascript" src="assets/liksomSymfony/jsCSSy/ui.resizable.js"></script>

	<script type="text/javascript" src="assets/liksomSymfony/jsCSS/fullcalendar.min.js"></script>

<script type='text/javascript'>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
       header: {
        left: 'prevYear,prev,today,next,nextYear',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      allDayDefault: false,
      allDaySlot: false,
      firstHour: 9,
      firstDay: 1,
      timeFormat: 'H(:mm)',
      //editable: true,
      monthNames:
        <?php echo __("['Januar','Februar','Mars','April','Mai','Juni','Juli','August','September','Oktober','November','Desember']") ?>,
      monthNamesShort:
        <?php echo __("['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Des']")?>,
      dayNames:
        <?php echo __("['Søndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag']")?>,
      dayNamesShort:
        <?php echo __("['Søn','Man','Tir','Ons','Tor','Fre','Lør']")?>
      ,
      eventDrop: function(calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
        alert(calEvent.title + ' was moved ' + dayDelta + ' days and '+ minuteDelta + ' minutes\n' +
                '(should probably update your database)');
        // Reverser endringene siden det ikke er implementert
        revertFunc();
      },
      eventResize: function(calEvent, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view){
        alert(calEvent.title + ' was resized by '+ dayDelta +' days and '+ minuteDelta + ' minutes');
        //Her kan vi eventuellt implementere drag-dropp av kalenderhendelser
        revertFunc();
      }
      ,
      loading: function(bool) {
        if (bool) $('#loading').show();
        else $('#loading').hide();
      },
//      events:
//        "<?php echo $routing->JSONevents()?>"
//      ,
      events: function(start, end, callback) {
        // hent data fra siden hvis det er første måned som skal vises;
//        if(!window.location.hash){
//          callback(initialEvents);
//          return;
//        }
        $.ajax({
            url: '<?php echo $routing->JSONevents()?>',
            dataType: 'json',
            data: {
                start: Math.round(start.getTime() / 1000),
                end: Math.round(end.getTime() / 1000),
                upub: $('#upubCheckbox').is(':checked')
            },

            success: function(doc) {
                callback(doc);
            }
        });
      }

      ,
      viewDisplay: function(view) {
        current_hash = false;
        window.location.hash = $.fullCalendar.formatDate( $('#calendar').fullCalendar('getDate'),'yyyy-M-d') + '?' + view.name;
        current_hash = window.location.href.split('#')[1];
      },
      year: parseHash().y,
      month: parseHash().m-1,
      date: parseHash().d,
      defaultView: parseHash().view,

      
      buttonText: {
        prev:     '&nbsp;&#9668;&nbsp;',  // left triangle
        next:     '&nbsp;&#9658;&nbsp;',  // right triangle
        prevYear: '&nbsp;&lt;&lt;&nbsp;', // <<
        nextYear: '&nbsp;&gt;&gt;&nbsp;', // >>
        today:    '<?php echo __('I dag')?>',
        month:    '<?php echo __('måned')?>',
        week:     '<?php echo __('uke')?>',
        day:      '<?php echo __('dag')?>'
      }

    });

$('#options').children().change( function(){$('#calendar').fullCalendar('refetchEvents')});
  });
  //fiks tilbake knappen slik at man kommer til den hendelsen man ønsket
  var current_hash = false;
  function check_hash() {
    if ( (current_hash != false) && (window.location.href.split('#')[1] != current_hash )) {
      current_hash = window.location.href.split('#')[1];
      var hash = parseHash();
      if(!$('#calendar').fullCalendar('getView') == hash.view){
        $('#calendar').fullCalendar( 'changeView',hash.view);
      }
      $('#calendar').fullCalendar( 'gotoDate', hash.y , hash.m - 1 , hash.d);
    }
  }

  function parseHash(){
    if(!window.location.href.split('#')[1]){
      var today = new Date();
      return {
        y:    today.getFullYear(),
        m:    today.getMonth() + 1,
        d:    today.getDate(),
        view: 'month'
      }
    };
    var arr = window.location.href.split('#')[1].split('?');
    return {
      y:    arr[0].toString().split('-')[0],
      m:    arr[0].toString().split('-')[1],
      d:    arr[0].toString().split('-')[2],
      view: arr[1]
    };
  }
   hashCheck = setInterval( "check_hash()", 100 );
   
   //legg med et array med hendelser for gjeldende måned så det går raskere å laste
//   initialEvents = <?php echo '' ?>;
</script>
<div id="loading"><?php echo __('Laster kalender')?></div>
<div id="calendar"></div>
<?php if($user->hasPermission('se upubliserte')):?>
<div id="options">
  <label for="upub">Vis Upubliserte</label><input type="checkbox" id="upubCheckbox" name="upubCheckbox" value="asdf"/>
</div>
<?php endif;?>


<noscript>
  <p>Du har desverre ikke JavaScript aktivert Her kan vi eventuellt lage en noScript kalender </p>
</noscript>
