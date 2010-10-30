<?php
//netpeans code completion
if(false){
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\DummyRouting();
}
// ===========================================================================
// ===  Script som henter inn kalenderdata for fremtiden og lagrer   =========
// ===  det som en iCal fil som folk kan inkludere i kalenderen sin.  ========
// ===========================================================================
//  Strukturen er som følger
//       1. Hent inndata (http GET 'type' og 'oea') og definer konstanter basert på inndata
//       3. Skriver header info
//       4. Skriver intro til iCalendar fila (kalendernavn, tidssone osv.)
//       5. Legger til alle eventene som iCal eventer, med utvalg basert på konstantene definert i 2.
//       6. Avslutter iCal fila

// Importerer biblioteket som brukes til generering av ICal filene
require_once dirname(__FILE__).'/../vendor/iCalcreator.class.php';




// ----------------------------------------------------------------
// ----- Henter inndata og definerer konstanter  ------------------
// -------  for kalenderfeeden som skal genereres -----------------
// ----------------------------------------------------------------

//Sett sammen feed fra beskrivelse i database. Standard er type normal.
    //Det blir også kontrollert at type bare inneholder alfanumeriske tegn for å hindre SQL injection
$type = ( isset($_GET['type']) && ctype_alnum($_GET['type'])) ? $_GET['type'] : "normal" ;
if ($type=="normal"){
//Setter alle variabler til false og navnet til normal
    $navn               = "normal";
    $unormal            = false;
    $upubliserte        = false;
    $ikke_bgjengen      = false;
    $styreans_titel     = false;
    $styreans_beskrivelse=false;
    $engelsk            = false;
    $intern_info        = false;
    $lovsang_titel      = false;
    $lovsang_beskrivelse= false;
    $oea                = false;
    $ikke_bgjengen      = false;
    $ikke_fredag        = false;
    $ikke_tur           = false;
    $ikke_bibel         = false;
    $ikke_sportsandakt  = false;
    $vis_utleie         = false;
    $bareUpub           = false;

}else{
    global $modx;
    $unormal            = true;
    $setup_querry       = 'Select * from iCal_parametere where navn = "' . $type. '"';
    $setup_result       = $modx->db->query($setup_querry);
    $setup_row          = $modx->db->getRow($setup_result);
    $upubliserte        = $setup_row['upubliserte'];
    $styreans_titel     = $setup_row['styreans_titel'];
    $styreans_beskrivelse=$setup_row['styreans_beskrivelse'];
    $engelsk            = $setup_row['engelsk'];
    $intern_info        = $setup_row['intern_info'];
    $lovsang_titel      = $setup_row['lovsang_titel'];
    $lovsang_beskrivelse= $setup_row['lovsang_beskrivelse'];
    $oea                = $setup_row['oea'];
    $ikke_bgjengen      = $setup_row['ikke_bgjengen'];
    $navn               = $setup_row['navn'];
    $ikke_fredag        = $setup_row['ikke_fredag'];
    $ikke_tur           = $setup_row['ikke_tur'];
    $ikke_bibel         = $setup_row['ikke_bibel'];
    $ikke_sportsandakt  = $setup_row['ikke_sportsandakt'];
    $vis_utleie         = $setup_row['vis_utleie'];
    $bareUpub           = $setup_row['bareUpub'];
}
if($engelsk){
  \Laget\Controller\BaseController::$__lang = 'en';
}else{
  \Laget\Controller\BaseController::$__lang = 'no';
}
date_default_timezone_set('Europe/Oslo');
// Array med alle versjonene av <br> som kan ligge i kalenderdatabasen, som må byttes ut med "\n\r"
$enter = array( "<br>" , "<br/>" , "<BR>" , "<BR/>" , "<Br>" , "<Br/>" ,
                "<bR>" , "<bR/>" , "<br />" , "<BR />" , "<Br />" , "<bR />" , "\r");
// Nøkkel for fjering av æøå for programmer som ikke takler dem riktig
$trans = array( 'æ' => 'ae' , 'Æ'=>'Ae','ø'=>'o','Ø'=>'O','å'=>'aa','Å'=>'Aa','ë'=>'e');
// Siden det ikke ligger noen stedsbeskrivelse i databasen settes defalult til Berg Prestegård
$sted="Berg Prestegård";

//=================================================================
//==============   BEGYNNER Å SKRIVE UT TIL FILA  =================
//=================================================================

//Skriver headere til utfila, slik at den kjennes igjen av kalenderprogramvare


//ex. cal.ics , calstyret.ics , callovsang.ics for å gjøre testing enklere.
$v = new vcalendar();                          // initiate new CALENDAR
$v->setConfig( 'unique_id'
             , 'laget.net' );             // config with site domain
$v->setProperty( 'X-WR-CALNAME'
               , 'TKS ' . $type );          // set some X-properties, name, content.. .
$v->setProperty( 'X-WR-CALDESC'
               , 'Kalender for laget.net som viser hendelsenen i kalenderen.' );
$v->setProperty( 'X-WR-TIMEZONE'
               , 'Europe/Oslo' );
$v->setProperty( 'CALSCALE'
               , 'GREGORIAN' );
$v->setProperty( 'method' , 'publish');


// --------------------------------------------------------------------------
// -- Gå gjennom alle radene i databasen og legg den inn som en ical event --
// --------------------------------------------------------------------------
foreach ($events as $event) {
    // ---------------------------------------------
    // ----Finn ut om hendelsen skal skrives ut ----
    // ---------------------------------------------
    $publiser = $event->isPublic();
    // Regler for hva som skal publisereres
    if($type != 'Normal'){
        if ($upubliserte || $bareUpub){
            $publiser = true;
        }
        if($bareUpub && $event->isPublic()){
            $publiser = false;
        }
        if($ikke_bgjengen && ($event->getType()->getId() == 2)){
            $publiser = false;//Berggjengen skal ikke skrives ut
        }elseif($ikke_fredag && ($event->getType()->getId() == 1)){
            $publiser = false;
        }elseif($ikke_tur && ($event->getType()->getId() == 3)){
            $publiser = false;
        }elseif($ikke_bibel && ($event->getType()->getId() == 4)){
            $publiser = false;
        }elseif($ikke_sportsandakt && ($event->getType()->getId() == 5)){
            $publiser = false;
        }elseif($vis_utleie && ($event->getType()->getId() == 6 || $event->getType()->getId() == 0)){
            $publiser = true;
        }

        //HER KAN DET LEGGES TIL FLERE BETINGELSER FOR OM HENDELSEN SKAL PUBLISERES ELLER IKKE
    }


    if($publiser) {
        // Sett sammen datafeltene $tittel og $lang_tekst basert på database og definerte konstanter
        // Først settes det sammen et grunnlag, før det eventuellt endres av en unormal kalender
        if($event->isPublic()){
            $titel = $event->getTitle();
        }else{
            $titel = "Upub " . $event->getTitle();
        }
        // lang_tekst sendes som description til ical etter at den første <br> er fjernet og resten byttet med CRLF \r\n
        $lang_tekst = "";
        if ($event->hasSpeaker()){
        $lang_tekst .= "\nTaler: " . $event->getSpeaker();
        }
        if ($event->hasShort()){
            $lang_tekst .="\n" . $event->getShort() . '\n';
        }
        if ($event->hasInfo()){
        $lang_tekst .= "\n" . $event->getInfo(false);
        }
        // ------------------------------------------------------------------
        // --Hvis det er en spesiell kalender kjøres mer kode på variablene.-
        // ------------------------------------------------------------------
        if ($unormal){
            // vis hvem som har styreansvar i tittel
            if ($styreans_titel){
                $titel .= " - " . $event->getResponsibilities('Ansvarlig',true);
            }
            // vis hvem som har styreansvar i beskrivelse
            if ($styreans_beskrivelse){
                $lang_teskt .= '<br>Styreansvarlig: ' . $event->getResponsibilities('Ansvarlig',true);
            }


            // legg til intern info
            if ($intern_info && $event->hasInternalInfo()){
                $lang_tekst .= '<br>Intern info:<br>' . $event->getInternalInfo();
            }
            // Endre æøå til ae oe aa hvis det er ønsket
            if ($oea){
                $titel = strtr($titel,$trans);
                $lang_tekst = strtr($lang_tekst,$trans);
                $sted = strtr($sted,$trans);
            }
        }//end if($unromal)



        //Forsøk på å konstruere urlen som inneholder eventinnfo på laget.net
        $url = $routing->showEvent($event->getRawValue());

        // Fjern første linjeskift<br> i $lang_tekst
        $lang_tekst = substr($lang_tekst,1);
        // Formater linjeskift på riktig måte for iCal formatet
        $lang_tekst = str_replace($enter, "\n" , $lang_tekst);

    // -----------------------------
    // ---- Skriver ut eventen -----
    // -----------------------------
        $e = new vevent();
        $e->setProperty( 'DTSTART',  $event->getStart('Ymd\THis'));
        $e->setProperty( 'DTEND' , $event->getEnd('Ymd\THis'));
        // tidsstempel for når feeden ble generert (Standarden krever det av en eller annen grunn for hvert element)
        $e->setProperty( 'DTSTAMP' , date('Ymd\THis'));
        // Unik ID som gir en global unik referanse til akkuratt denne eventen (forutsetter at vi aldri nulstiller id i kalenderbasen)
        $e->setProperty( 'UID' , $event->getId() . "@laget.net/ical?type=" . $navn);
        $e->setProperty( 'CLASS' , 'PUBLIC');
        // Dato eventen ble opprettet
        $e->setProperty( 'CREATED' , $event->getCreated('Ymd\THis'));
        $e->setProperty( 'DESCRIPTION' , strip_tags($lang_tekst));
        // Dato eventen sist ble endret
        $e->setProperty( 'LAST-MODIFIED:' , $event->getEdited("Ymd\THis"));
        $e->setProperty( 'LOCATION:' , strip_tags($sted));
        // tall som forteller versjonen av eventen som blir sendt
        $e->setProperty( 'SEQUENCE' , $event->getVersion());
        $e->setProperty( 'STATUS' , 'CONFIRMED');
        $e->setProperty( 'SUMMARY' , strip_tags($titel));
        $e->setProperty( 'TRANSP' , 'OPAQUE');

      $v->setComponent( $e );
    }//end if(publiser)
}//end while
//Avslutt fila
$str = $v->createCalendar();
//header_remove();
header('Content-type: text/calendar; charset=utf-8');
header("Content-Disposition: inline; filename=lagetnet$type.ics");
echo $str;
//exit();