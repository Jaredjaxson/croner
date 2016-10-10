<?php
/*
autor Jared Jackson
*/

include_once 'croner.php';

$cron = new croner();

$cron->AddTaskClock(300,'BotScan','','BotScan');
$cron->AddTaskClock('14:30','died','','die');

$cron->StartCron();

// functione de la bote
function BotScan(){
	echo 'Start BotScan'.date('H:i').'\n';
}

function died(){
	echo 'bot die in '.date('H:i').'\n';
  die();
}
?>
