<?php
require_once dirname(__FILE__)."/../vendor/phpqrcode.php";

QRcode::png("BEGIN:VEVENT\r\nSUMMARY:".$event->getTitle()."\r\nDTSTART:".$event->getStart("Ymd\THis")."\r\nDTEND:".$event->getEnd("Ymd\THis")."\r\nEND:VEVENT");
exit(0);