<?php

require_once "./Google/Config.php";
require_once "./Google/Client.php";
require_once "./Google/Service.php";
require_once "./Google/Model.php";
require_once "./Google/Utils.php";
require_once "./Google/Exception.php";
require_once "./Google/Service/Exception.php";
require_once "./Google/Collection.php";
require_once "./Google/Service/Resource.php";
require_once "./Google/Service/Calendar.php";
require_once "./Google/Auth/AssertionCredentials.php";
require_once "./Google/Auth/Abstract.php";
require_once "./Google/Auth/OAuth2.php";
require_once "./Google/Utils/URITemplate.php";
require_once "./Google/Http/REST.php";
require_once "./Google/Http/Request.php";
require_once "./Google/Http/CacheParser.php";
require_once "./Google/Cache/Abstract.php";
require_once "./Google/Cache/File.php";
require_once "./Google/Signer/Abstract.php";
require_once "./Google/Signer/P12.php";
require_once "./Google/IO/Abstract.php";
require_once "./Google/IO/Curl.php";


// Service Account info
$client_id = '235839918221-9ml35q5pofdlapg68apb5gn30dq73gcd.apps.googleusercontent.com';
$service_account_name = '235839918221-9ml35q5pofdlapg68apb5gn30dq73gcd@developer.gserviceaccount.com';
$key_file_location = 'Thinkful-Email-Reminders-5bd170bca0ae.p12';

// Calendar id
$calName = 'mshull@thinkful.com';


$client = new Google_Client();
$client->setApplicationName("Calendar Test");

$service = new Google_Service_Calendar($client);

$key = file_get_contents($key_file_location);
$cred = new Google_Auth_AssertionCredentials(
 $service_account_name,
 array('https://www.googleapis.com/auth/calendar.readonly'),
 $key
);

$client->setAssertionCredentials($cred);

$cals = $service->calendarList->listCalendarList();
//print_r($cals);
//exit;

$date=date("Y-m-d"); 
$nextDate = date('Y-m-d', strtotime($date . ' + 1 day'));

$timeMin = $date."T11:00:00-06:00";
$timeMax = $nextDate."T01:59:59-06:00";

$optParams = array(
		'timeMin' => $timeMin,
		'timeMax' => $timeMax,
		'singleEvents' => "true",
		'orderBy' => "startTime"
	);
$events = $service->events->listEvents($calName, $optParams);

foreach ($events->getItems() as $event) {
	foreach ($event->getAttendees() as $attendee) {
		$name = explode(" ",$attendee->displayName);
		$name = $name[0];
		$date = new DateTime($event->getStart()->getDateTime());
	 	$time = $date->format('g:ia');;
		$subject = 'Mentor Session';

		$messages=array(
			"Hey $name,<br><br>Just wanted to send you a quick email and remind you about our session at $time CST.  See you then!
			<br>
			<h3 style='margin:0;'>Matt Shull</h3>
			FEWD & Angular Mentor @ <a href='http://www.thinkful.com' target='_blank'>Thinkful.com</a>
			<br>Co-Creator and Contributor @ <a href='http://www.rhinoio.com' target='_blank'>RhinoIO.com</a>",
			"Hey $name,<br><br>Wanted to remind you of our session at $time CST.  See you soon!
			<br>
			<h3 style='margin:0;'>Matt Shull</h3>
			FEWD & Angular Mentor @ <a href='http://www.thinkful.com' target='_blank'>Thinkful.com</a>
			<br>Co-Creator and Contributor @ <a href='http://www.rhinoio.com' target='_blank'>RhinoIO.com</a>",
			"Hey $name,<br><br>Just wanted to remind you about our session later at $time CST.  See you then!
			<br>
			<h3 style='margin:0;'>Matt Shull</h3>
			FEWD & Angular Mentor @ <a href='http://www.thinkful.com' target='_blank'>Thinkful.com</a>
			<br>Co-Creator and Contributor @ <a href='http://www.rhinoio.com' target='_blank'>RhinoIO.com</a>",
			"Hey $name,<br><br>Don't forget about our session later on at $time CST.  See you soon!
			<br>
			<h3 style='margin:0;'>Matt Shull</h3>
			FEWD & Angular Mentor @ <a href='http://www.thinkful.com' target='_blank'>Thinkful.com</a>
			<br>Co-Creator and Contributor @ <a href='http://www.rhinoio.com' target='_blank'>RhinoIO.com</a>",
			"Hey $name,<br><br>Quick reminder about our session at $time CST.  See you then!
			<br>
			<h3 style='margin:0;'>Matt Shull</h3>
			FEWD & Angular Mentor @ <a href='http://www.thinkful.com' target='_blank'>Thinkful.com</a>
			<br>Co-Creator and Contributor @ <a href='http://www.rhinoio.com' target='_blank'>RhinoIO.com</a>"
		);
		shuffle($messages);
		$message=($messages[0]);

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "Bcc: Matt Shull <mshull@thinkful.com>" . "\r\n";
		$headers .= 'From: Matt Shull <mshull@thinkful.com>' . "\r\n";
		$headers .= 'Reply-To: mshull@thinkful.com' . "\r\n";

		mail ($attendee->email, $subject, $message, $headers);
	}
}

?>
