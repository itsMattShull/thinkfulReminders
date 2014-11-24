thinkfulReminders
=================

Script used to remind students and the mentor of the days sessions.

In Google Calendars just set the time and invite the student to the event.  When you run the emailDay.php file it will look for all sessions that occur between 11am CST and 2am CST (The next day).  You can change these times by changing the $timeMin and $timeMax variables.

emailNight.php will email students whose sessions occur between 2am CST and 11am CST.  Run the script in the evening of the day before because it says "see you tomorrow" in the messages.

In emailDay and emailNight make sure to provide the following information:
+   Client ID (Found by registering for Google Calendar API)
+   Service Account Name (Found by registering for Google Calendar API)
+   Key File Location (Found by registering for Google Calendar API)
+   Calendar ID (Found in calendar settings)
