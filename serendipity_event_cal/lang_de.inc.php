<?php

# serendipity_event_cal.php, langfile(de) v1.65 2015-11-25 Ian

@define('PLUGIN_EVENTCAL_HEADLINE', 'Kopfzeile (optional)');
@define('PLUGIN_EVENTCAL_HEADLINE_BLAHBLAH', 'Was f�r eine �berschrift soll die Seite haben?');
@define('PLUGIN_EVENTCAL_TITLE', 'Event-Terminkalender');
@define('PLUGIN_EVENTCAL_TITLE_BLAHBLAH', 'Zeigt einen Terminkalender innerhalb des Blogs mit dem Blog-Design und allen Formatierungen an. Unterst�tzt Backend/Frontend Administration, vielfache Termine und iCal Export. (Nur MySQL)');
@define('PLUGIN_EVENTCAL_PERMALINK', 'Permalink');
@define('PLUGIN_EVENTCAL_PERMALINK_BLAHBLAH', 'Gibt den Permalink der statischen Seite an. Dieser muss eine absolute Pfadangabe vom HTTP-Root ab sein und die Dateiendung .htm oder .html besitzen!');
@define('PLUGIN_EVENTCAL_PAGETITLE', 'Seitentitel und URL');
@define('PLUGIN_EVENTCAL_PAGETITLE_BLAHBLAH', 'Titel der Seite. Achtung: ohne Sonder- und Leerzeichen, da auch f�r die Adresszeile a la (index.php?serendipity[subpage]=name) benutzt.');
@define('PLUGIN_EVENTCAL_ARTICLEFORMAT', 'Als Artikel formatieren?');
@define('PLUGIN_EVENTCAL_ARTICLEFORMAT_BLAHBLAH', 'Legt fest, ob die Ausgabe automatisch wie ein Artikel formatiert werden soll (nur die main divs.) (Standard: ja)');
@define('PLUGIN_EVENTCAL_SHOWINTRO', 'Einf�hrungstext (optional)');
@define('PLUGIN_EVENTCAL_SHOWINTRO_BLAHBLAH', 'Text (HTML erlaubt), der vor den Eintr�gen angezeigt werden soll.');
@define('PLUGIN_EVENTCAL_SHOWCAPTCHA', 'Captcha-Schutz aktivieren?');
@define('PLUGIN_EVENTCAL_SHOWCAPTCHA_BLAHBLAH', 'Soll der Eventkalender den Captcha-Schutz benutzen? Dies setzt ein aktiviertes Spamblock-Plugin voraus!');
@define('PLUGIN_EVENTCAL_SHOWICAL', 'Exportiere als iCal Feed?');
@define('PLUGIN_EVENTCAL_SHOWICAL_BLAHBLAH', 'Legt fest, ob Monatszusammenstellungen oder Einzelevents via iCal export �ber Buttons im Frontend erlaubt sind.');
@define('PLUGIN_EVENTCAL_ICAL_LOG', 'Protokolliere iCal exports?');
@define('PLUGIN_EVENTCAL_ICAL_LOG_BLAHBLAH', 'Legt fest, ob iCal Export Anfragen protokolliert werden.');
@define('PLUGIN_EVENTCAL_ICAL_LOG_EMAIL', 'Admin Email Adresse');
@define('PLUGIN_EVENTCAL_ICAL_LOG_EMAIL_BLAHBLAH', 'Email Adresse, an die ein iCal Export Request gesendet wird. Wenn leer, wird nur in Datei geloggt.');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL', 'Export iCal URL?');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_BLAH', 'Legt fest, wie die angeforderte iCal Exportdatei gesendet werden soll.');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_BLAHBLAH', 'Als Download f�r den User, Webcal-Push f�r den User, Email (�ber Frontend an Admin - Adresse muss unten oder in \'Pers�nliche Einstellungen\' - Alias: john@example.com gesetzt sein) oder via Benutzers eigener Entscheidung (alle 3 M�glichkeiten).');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_NO',    'kein ics file');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_DL',  'ics Download');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_WEBCAL',  'ics �ber webcal://');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_MAIL',  'ics �ber mail');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_USER',  'user entscheidet');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_EXPORT',  'an user');
@define('PLUGIN_EVENTCAL_ICAL_ICSURL_INLIST_INTERN',  'an admin');

@define('PLUGIN_EVENTCAL_NEXTPAGE', 'n�chste Seite');
@define('PLUGIN_EVENTCAL_PREVPAGE', 'vorherige Seite');
@define('PLUGIN_EVENTCAL_TEXT_DELETE', 'l�schen');
@define('PLUGIN_EVENTCAL_TEXT_SAY', 'schrieb');
@define('PLUGIN_EVENTCAL_TEXT_EMAIL', 'E-mail');
@define('PLUGIN_EVENTCAL_TEXT_NAME', 'Name');
@define('PLUGIN_EVENTCAL_TEXT_EACH', 'Jeden');
@define('PLUGIN_EVENTCAL_TEXT_TO', 'bis');
@define('PLUGIN_EVENTCAL_TEXT_INTERVAL', 'Intervall');
@define('PLUGIN_EVENTCAL_TEXT_BIWEEK', 'zweiw�chentlich');
@define('PLUGIN_EVENTCAL_TEXT_YEARLY', 'j�hrlich');
@define('PLUGIN_EVENTCAL_TEXT_CW', 'KW-');

@define('PLUGIN_EVENTCAL_HALLO_ADMIN', 'Hallo Benutzer: %s ( %s )<br />');
@define('PLUGIN_EVENTCAL_INSERT_DONE_BLAHBLAH', 'Vielen Dank f�r ihren Termin mit der ID %d.');
@define('PLUGIN_EVENTCAL_INSERT_DONE_EVALUATE', 'Bis er evaluiert wurde, finden Sie ihn im Bereich: "Termin best�tigen".');
@define('PLUGIN_EVENTCAL_REJECT_DONE_BLAHBLAH', 'Sie haben den Eintrag mit der ID %d erfolgreich aus der Datenbank gel�scht.');
@define('PLUGIN_EVENTCAL_APPROVE_DONE_BLAHBLAH', 'Der Eintrag mit der ID %d wurde erfolgreich genehmigt.');
@define('PLUGIN_EVENTCAL_SENDMAIL_BLAHBLAH', 'Die iCal Datei wurde erfolgreich versandt!');
@define('PLUGIN_EVENTCAL_SENDMAIL_ERROR', 'Ein Problem trat beim Versenden der Mail auf!');

@define('CAL_EVENT_PLEASECORRECT', 'Wir bitten um Korrektur.');
@define('CAL_EVENT_SHORTTITLE', 'Sie m�ssen einen Kurztitel f�r den Termin einf�gen!');
@define('CAL_EVENT_EVENTDESC', 'Sie m�ssen eine genaue Terminbeschreibung f�r den Termin einf�gen!');
@define('CAL_EVENT_APPBY', 'Sie m�ssen einen Autorenk�rzel f�r die Best�tigung des Termines einf�gen!');
@define('CAL_EVENT_START_DATE', 'Ung�ltiger Terminbeginn!');
@define('CAL_EVENT_START_RECUR', 'Das Startdatum muss dem &raquo; <u>%s</u> &laquo; (Tag %s) des ersten Vorkommens entsprechen!');
@define('CAL_EVENT_START_DATE_HISTORY', 'Event liegt zu weit (31 Tage ++) in der Vergangenheit!');
@define('CAL_EVENT_END_DATE', 'Ung�ltiges Terminende!');
@define('CAL_EVENT_REAL_START_DATE', 'Der Termin Starttag muss einem g�ltigen Tag des gegebenen Monats (%s) entsprechen!');
@define('CAL_EVENT_REAL_END_DATE', 'Das Terminende muss zeitlich nach dem Terminbeginn liegen und einem existierenden Tag des Monats (%s) entsprechen!');
@define('CAL_EVENT_REAL_MONTHLY_DATE', 'Die wiederholende Einstellung f�r monatliche Termine darf nicht "W�chentlich" lauten!');
@define('CAL_EVENT_IDENTICAL_DATE', 'Ihr Termin hat identische Start- und Endzeiten!');
@define('CAL_EVENT_ORDER_DATE', 'Ihre eingegebene Terminfolge ist nicht g�ltig!');
@define('CAL_EVENT_WEEKLY_DATE', 'Die korrekte Einstellung muss lauten: "W�chentlich" und gew�hlter "Wochentag".');
@define('CAL_EVENT_FALSECAPTCHA', 'Die Spamschutz-Grafik-Zeichen stimmen nicht �berein!');

@define('CAL_EVENT_FORM_DAY_FIRST', 'Ersten');
@define('CAL_EVENT_FORM_DAY_SECOND', 'Zweiten');
@define('CAL_EVENT_FORM_DAY_THIRD', 'Dritten');
@define('CAL_EVENT_FORM_DAY_FOURTH', 'Vierten');
@define('CAL_EVENT_FORM_DAY_LAST', 'Letzten');
@define('CAL_EVENT_FORM_DAY_SECONDLAST', 'Zweitletzten');
@define('CAL_EVENT_FORM_DAY_THIRDLAST', 'Drittletzten');
@define('CAL_EVENT_FORM_DAY_EACH', 'W�chentlich');

@define('CAL_EVENT_FORM_RIGHT_SHORTMAX', 'max. 16 Zeichen!');
@define('CAL_EVENT_FORM_RIGHT_RECURSTRICT1', 'Alle Termine m�ssen dem genauen Start-');
@define('CAL_EVENT_FORM_RIGHT_RECURSTRICT2', 'Datum des ersten Vorkommens entsprechen!');
@define('CAL_EVENT_FORM_RIGHT_URLDESC', 'Entweder als');
@define('CAL_EVENT_FORM_RIGHT_URL', 'http://www.domain.de');
@define('CAL_EVENT_FORM_RIGHT_MAIL', 'mailto:ihre@email.de');
@define('CAL_EVENT_FORM_RIGHT_OR', 'oder');
@define('CAL_EVENT_FORM_RIGHT_DETAILDESC', 'Vergessen Sie bitte nicht, die genaue Uhrzeit des Termins mit in dieses Textfeld oder in den Kurztitel zB. \'19:00 text\' zu setzen.');
@define('CAL_EVENT_FORM_RIGHT_BBC', 'Einfache BBcode Maskierung (Fett, Kursiv, Unter-, Durchgestrichen).');
@define('CAL_EVENT_FORM_RIGHT_SINGLE', 'Nur einen Tag');
@define('CAL_EVENT_FORM_RIGHT_SINGLE_NOEND', 'kein Enddatum ben�tigt');
@define('CAL_EVENT_FORM_RIGHT_MULTI', 'Mehrt�giger Termin');
@define('CAL_EVENT_FORM_RIGHT_RECUR', 'Sich wiederholend');
@define('CAL_EVENT_FORM_RIGHT_RECUR_MONTH', 'jeden Monat');
@define('CAL_EVENT_FORM_RIGHT_RECUR_WEEK', 'jede Woche');
@define('CAL_EVENT_FORM_RIGHT_RECUR_BIWEEK', 'jede 2. Woche');
@define('CAL_EVENT_FORM_RIGHT_RECUR_YEAR', 'jedes Jahr');

@define('CAL_EVENT_FORM_RIGHT_HELP_SINGLE', 'Einzeltermin. Ben�tigt kein \'Enddatum\' und keine weitere Spezifizierung!');
@define('CAL_EVENT_FORM_RIGHT_HELP_MULTI', 'Mehrt�giger Termin: Anzeige monatlich. Ben�tigt \'Startdatum\' und \'Enddatum\'.');
@define('CAL_EVENT_FORM_RIGHT_HELP_WEEK', 'W�chentlicher Termin. Nur m�glich mit der Einstellung: \'immer am\', \'W�chentlich\' und \'Wochentag\'. Anzeige jede Kalenderwoche. Ben�tigt \'Startdatum\' und \'Enddatum\'.');
@define('CAL_EVENT_FORM_RIGHT_HELP_BIWEEK', 'W�chentlicher Termin. Nur m�glich mit der Einstellung: \'immer am\', \'W�chentlich\' und \'Wochentag\'. Anzeige jede 2. Kalenderwoche. Ben�tigt \'Startdatum\' und \'Enddatum\'.');
@define('CAL_EVENT_FORM_RIGHT_HELP_MONTH', 'Monatlicher Termin. Nur m�glich mit der Einstellung: \'immer am\', \'xten\' und \'Wochentag\'. Anzeige jeden Monat. Ben�tigt \'Startdatum\' und \'Enddatum\'.');
@define('CAL_EVENT_FORM_RIGHT_HELP_YEAR', 'J�hrlicher Einzeltermin: Anzeige jedes Jahr ab Startdatum. Ben�tigt kein \'Enddatum\' und keine weitere Spezifizierung!');

@define('CAL_EVENT_FORM_BUTTON_ADD_EVENT', 'Termin hinzuf�gen');
@define('CAL_EVENT_FORM_BUTTON_APPROVE_EVENT', 'Termin best�tigen');
@define('CAL_EVENT_FORM_BUTTON_CLOSE', 'Formular zu');
@define('CAL_EVENT_FORM_BUTTON_FREETABLE', 'Alte Daten l�schen (> 1 Monat) und Tabelle neu strukturieren');
@define('CAL_EVENT_FORM_BUTTON_LOGOFF', 'ausloggen');
@define('CAL_EVENT_FORM_BUTTON_MARK', 'alle markieren / unmarkieren');
@define('CAL_EVENT_FORM_BUTTON_OPEN', 'Formular auf');
@define('CAL_EVENT_FORM_BUTTON_REJECT_SED', 'L�sche genehmigten Event Eintrag');
@define('CAL_EVENT_FORM_BUTTON_EDIT_SED', '�ndere genehmigten Event Eintrag');
@define('CAL_EVENT_FORM_BUTTON_SUBMIT', '&raquo; Eintrag abschicken &laquo;');
@define('CAL_EVENT_FORM_BUTTON_TOAPPROVE', 'Termin(e) anh�ngig');
@define('CAL_EVENT_FORM_BUTTON_HELP_ICALM', 'ICal Download der Termine des gegebenen Monats inklusive der wiederholenden Termine in Vergangenheit und Zukunft.');

@define('CAL_EVENT_FORM_TITLE_DATE', 'datum');
@define('CAL_EVENT_FORM_TITLE_TITLE', 'titel');
@define('CAL_EVENT_FORM_TITLE_DESC', 'beschreibung');
@define('CAL_EVENT_FORM_TITLE_URL', 'url');
@define('CAL_EVENT_FORM_TITLE_OK', 'ok');
@define('CAL_EVENT_FORM_TITLE_EDIT', 'edit');
@define('CAL_EVENT_FORM_TITLE_DEL', 'erase');

@define('CAL_EVENT_FORM_LEFT_AUTHOR', '<u>Aut</u>or');
@define('CAL_EVENT_FORM_LEFT_TITLE', '<u>Kurz</u>titel');
@define('CAL_EVENT_FORM_LEFT_LINK', 'Url od. Email');
@define('CAL_EVENT_FORM_LEFT_DESC', '<u>Aus</u>f�hrliche Beschreibung');
@define('CAL_EVENT_FORM_LEFT_SINGLE', '<u>Start</u>datum');
@define('CAL_EVENT_FORM_LEFT_MULTI', '<u>End</u>datum');
@define('CAL_EVENT_FORM_LEFT_RECUR', 'immer am');
@define('CAL_EVENT_FORM_LEFT_SPAM', 'Spamschutz');

@define('CAL_EVENT_DB_ERROR_ONE', 'DB Fehler in Kalender Tabelle gemeldet:');
@define('CAL_EVENT_DB_ERROR_TWO', 'Datenbankzugriff im Moment leider nicht m�glich!');
@define('CAL_EVENT_USER_LOGINFIRST', 'F�r die Terminadministration m�ssen sie sich erst in die Verwaltung des Blogs einloggen.');
@define('CAL_EVENT_USER_LOGINFIRST', 'Die Bearbeitung dieses Vorgangs bedarf eines angemeldeten Benutzers. Falls Sie einen zugelassenen Account auf diesem System haben, loggen Sie sich bitte erst in die Verwaltung ein.');
@define('CAL_EVENT_USER_VALIDATION', 'Ihr Username oder Ihr Passwort wurde nicht korrekt eingegeben.');
@define('CAL_EVENT_USER_LOGGEDOFF', 'Sie haben sich vom System abgemeldet oder ihre Session ist abgelaufen. Um den Terminkalender erneut zu administrieren, m�ssen Sie sich neu in die Verwaltung des Blogs einloggen.');
@define('CAL_EVENT_USER_FREETABLE', 'Sie haben alte Daten von �ber einem Monat gel�scht und die Event Datenbank-Tabelle mit %d verbliebenen Datens�tzen neu strukturiert.');
@define('CAL_EVENT_USER_FREE_SURE', 'Sollen die alten Daten gel�scht und die Event Datenbank-Tabelle wirlich neu strukturiert werden?<br />Achtung: dies hat negative Auswirkungen auf Suchmaschinen und sonstige Dienste!');
@define('CAL_EVENT_USER_NOPERMISSION', 'Sie haben keine Berechtigung diesen Vorgang fortzusetzen!');
@define('CAL_EVENT_CHGSELECTED_ARRAY', 'Sie wollen einen Eintrag bearbeiten, haben aber alle oder mehrere angeklickt.');
@define('CAL_EVENT_CHECKBOXALERT', 'Wenn Sie einen unver�ffentlichten Eintrag freigeben, �ndern oder l�schen wollen, m�ssen Sie die Checkbox des Eintrags vorher aktivieren.');

@define('CAL_EVENT_TODAY', 'HEUTE');
@define('PLUGIN_EVENTCAL_CAL', ' Draw calendar ');
@define('PLUGIN_EVENTCAL_ADD', ' Draw add ');
@define('PLUGIN_EVENTCAL_APP', ' Draw app ');

/* Backend main constants */
@define('PLUGIN_EVENTCAL_ADMIN_NAME', 'Event Kalender');
@define('PLUGIN_EVENTCAL_ADMIN_NAME_MENU', 'Event Kalender  v.%s - Backend Administration Menu');
@define('PLUGIN_EVENTCAL_ADMIN_DBC', 'Event Kalender - Plugin Administration');
@define('PLUGIN_EVENTCAL_ADMIN_VIEW', 'Event Kalender - Termine ansehen');
@define('PLUGIN_EVENTCAL_ADMIN_VIEW_DESC', 'Sortiert nach event type (tipo) single, multi, recur, weekly, yearly - aufsteigend.');
@define('PLUGIN_EVENTCAL_ADMIN_ORDERBY_DESC', 'Sortiert nach event type (timestamp) absteigend.');
@define('PLUGIN_EVENTCAL_ADMIN_APP', 'Event Kalender - Termine best�tigen');
@define('PLUGIN_EVENTCAL_ADMIN_APP_DESC', 'Sortiert nach Datum des Eintrags [ j�ngster oben ].');
@define('PLUGIN_EVENTCAL_ADMIN_ERASE', 'Event Kalender - Termine l�schen');
@define('PLUGIN_EVENTCAL_ADMIN_LOG', 'Event Kalender - iCal Log');
@define('PLUGIN_EVENTCAL_ADMIN_LOG_ERROR', 'ACHTUNG: Beim Schreiben der iCal.log Datei trat ein Fehler auf. Bitte schauen sie ob Datei und Ordner beschreibbar sind.');
@define('PLUGIN_EVENTCAL_ADMIN_ADD', 'Event Kalender - Termine eintragen');
@define('PLUGIN_EVENTCAL_ADMIN_NORESULT', 'Es liegen keine Termine %s vor!');
@define('PLUGIN_EVENTCAL_ADMIN_NORESULT_APP', 'zum Best�tigen');
@define('PLUGIN_EVENTCAL_ADMIN_NORESULT_DROP', 'zum L�schen');
@define('PLUGIN_EVENTCAL_ADMIN_NORESULT_FREE', 'zum Bereinigen');
@define('PLUGIN_EVENTCAL_ADMIN_FREE_SURE', 'Wollen Sie wirklich die Eventcal Tabelle von alten Terminen befreien?');
@define('PLUGIN_EVENTCAL_ADMIN_CLEAN_SURE', 'Wollen Sie wirklich die Autoincrement Werte (id) der Eventcal Tabelle neu strukturieren?');
@define('PLUGIN_EVENTCAL_ADMIN_CLEAN_SURE_ADD', '<u>Achtung:</u> Dies hat negative Auswirkungen auf eventuell gespeicherte Daten von Suchmaschinen und sonstiger Dienste!');
@define('PLUGIN_EVENTCAL_ADMIN_DROP_SURE', 'Wollen Sie die Eventcal Tabelle mit allen Daten wirklich vollst�ndig l�schen? Bitte best�tigen Sie dies hier!');
@define('PLUGIN_EVENTCAL_ADMIN_DROP_OK', 'Ihre Datenbank %s wurde erfolgreich gel�scht!');
@define('PLUGIN_EVENTCAL_ADMIN_DUMP_SELF', 'Vor der Ausf�hrung sollten Sie sich zur Sicherheit via PhpMyAdmin einen mysql_dump ihrer Daten anlegen!');
@define('PLUGIN_EVENTCAL_ADMIN_ICAL_EMAILLINK', 'Download aller genehmigten Events als ics Datei via Email an Admin, wenn in Plugins Config gesetzt! Sonst Fallback als Download.');
@define('PLUGIN_EVENTCAL_ADMIN_ICAL_DOWNLINK', 'Download aller genehmigten Events als ics Datei!');
/* backend database (dbc) administration constants */
@define('PLUGIN_EVENTCAL_ADMIN_DBC_TITLE', 'Seien sie behutsam in der Benutzung dieser Admin Links.');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_TITLE_DESC', 'Manche Links k�nnten mit zuk�nftigen Versionen ihr Verhalten �ndern!');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DUMP', 'Administration - sichern');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DUMP_DESC', 'Backup der Eventcal Datenbanktabelle');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DUMP_TITLE', 'Verwaltung der Eventcal Datenbank Sicherung');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DUMP_DONE', 'Ihre Eventcal Datenbank Tabelle wurde erfolgreich nach templates_c gesichert!');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DUMP_MSG', 'Ein Datenbank Backup ist nicht trivial. Benutzen sie bitte Werkzeuge wie PhpMyAdmin um ihre Daten zu sichern!');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INSERT', 'Administration - eintragen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INSERT_DESC', 'Eintrag in die Eventcal Datenbanktabelle');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INSERT_TITLE', 'Erstellen eines Events');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INSERT_MSG', 'Das Einf�gen eines Datenbank Backups ist nicht trivial. Benutzen sie bitte Werkzeuge wie PhpMyAdmin um ihre Daten in die Datenbank zu �bernehmen!');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ERASE', 'Administration - l�schen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ERASE_DESC', 'L�schen der Eventcal Datenbanktabelle');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ERASE_TITLE', 'Tabelle l�schen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DELFILE_MSG', 'Datenbank SQL Datei <u>%s</u> erfolgreich gel�scht');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DELOLD', 'Administration - aufr�umen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DELOLD_DESC', 'L�schen alter Termine (> 1 Monat) aus der Datenbanktabelle');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DELOLD_TITLE', 'Alte Termine l�schen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DELOLD_MSG', 'Sie haben %d alte Termine von �ber 30 Tagen in der Vergangenheit aus der Event Datenbank-Tabelle gel�scht.');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DOWNLOAD', 'Administration - verwalten');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DOWNLOAD_DESC', 'Download und l�schen der letzten Datenbank Sicherungen');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_DOWNLOAD_MSG', 'Kein Ordner eventcal in templates_c vorhanden.');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INCREMENT', 'Administration - strukturieren');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INCREMENT_DESC', 'Setzen sie neue Increment ID\'s f�r alle Termine in der Datenbank');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INCREMENT_TITLE', 'Increment ID\'s neu strukturieren');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_INCREMENT_MSG', 'Sie haben die Event Datenbank-Tabelle mit %d verbliebenen Datens�tzen neu strukturiert.');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ICALALL', 'Administration - iCal');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ICALALL_DESC', 'Senden aller genehmigten Termine als iCal Datei an Admin - via Email, wenn in Config gesetzt, sonst als Download');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ICALALL_TITLE', 'Herunterladen aller iCal Termine');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ILOG', 'Administration - iLog');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ILOG_DESC', 'Ansehen der iCal Export-Logdatei, falls existent');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ILOG_TITLE', 'ICal Export-Logdatei');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_ILOG_MSG', 'Die iLog Datei existiert nicht!');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_NIXDA_DESC', 'Es ist keine Eventcal Datenbanktabelle vorhanden');
@define('PLUGIN_EVENTCAL_ADMIN_DBC_NIXDA_TITLE', 'Administration - error');

@define('PLUGIN_EVENTCAL_EVENTWRAPPER', 'Erlaube Eventwrapper Plugin Ausgabe?');
@define('PLUGIN_EVENTCAL_EVENTWRAPPER_BLAHBLAH', 'Plaziert mit Hilfe des serendipity_plugin_eventwrapper Seitenleisten Plugins die Kalender Ereignisse des aktuellen Monats als Links in die Seitenleiste. Aus Performancegr�nden bitte nur dann erlauben!');
