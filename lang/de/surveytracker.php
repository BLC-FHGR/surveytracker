<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Survey Tracker mod
 *
 * @package    mod_surveytracker
 * @copyright  Copyright (c) 2022 Open LMS (https://www.openlms.net/)
 * @author     FHGR - Marc-Alexander Iten
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Umfragebörse';
$string['modulenameplural'] = 'Umfragebörsen';
$string['modulename_help'] = 'Mit der Umfragebörse können Studierende externe Umfragen zur Verfügung stellen. Alle Studierenden können an diesen Umfragen teilnehmen und erhalten Punkte für deren Teilnahme.';
$string['modulename_link'] = 'mod/surveytracker/view';
$string['pluginname'] = 'Umfragebörse';
$string['pluginadministration'] = 'Umfragebörse Administration';
$string['surveytracker'] = 'Umfragebörse';
$string['userlist'] = 'Teilnehmerliste';
$string['showuserlist'] = 'Teilnehmerliste anzeigen';

$string['surveytracker:addinstance'] = 'Umfragebörse hinzufügen';
$string['surveytracker:myaddinstance'] = 'Umfragebörse hinzufügen';
$string['surveytracker:view'] = 'Umfragebörse sehen';
$string['surveytracker:editingasurvey'] = 'Umfrage bearbeiten';
$string['privacy:metadata'] = 'Survey Tracker mod does not store data itself.';
$string['surveyrunning'] = 'Es läuft bereits eine Umfrage';
$string['surveysolved'] = 'Umfrage wurde erfolgreich abgeschlossen';
$string['surveysolvedearlier'] = 'Umfrage wurde schon einmal abgeschlossen';

$string['modulevisibility'] = 'Modulsichtbarkeit';
$string['visibility'] = 'Sichtbarkeit';
$string['surveytracker:global'] = 'Global';
$string['surveytracker:moduleonly'] = 'Nur für diesen Kurs';

$string['table:survey'] = 'Umfrage';
$string['table:points'] = 'Punkte';
$string['table:expires'] = 'Läuft ab am';
$string['table:add'] = 'Umfrage hinzufügen';
$string['table:edit'] = 'bearbeiten';
$string['table:participants'] = 'Teilnehmer';
$string['table:clicktocopy'] = 'zum kopieren klicken';
$string['table:copied'] = 'kopiert';
$string['table:firstname'] = 'Vorname';
$string['table:lastname'] = 'Nachname';
$string['table:amount'] = 'Anzahl';
$string['table:surveys'] = 'erstellte Umfragen';
$string['table:surveyparticipations'] = 'Teilnahmen';
$string['table:surveyparticipationspoints'] = 'verteilte Punkte';
$string['table:contestant'] = 'eigene Teilnahmen';
$string['table:contestantcount'] = 'Teilnahmen';
$string['table:contestantpoints'] = 'erhaltene Punkte';
$string['table:export'] = 'als .xlsx exportieren';

$string['edit:surveyurl'] = 'Link zur Umfrage';
$string['edit:subject'] = 'Titel';
$string['edit:description'] = 'Beschreibung';
$string['edit:expiry'] = 'Ablaufdatum und -zeit';
$string['edit:points'] = 'Punkte';
$string['surveyedited'] = 'Umfrage wurde bearbeitet';
$string['surveyadded'] = 'Umfrage wurde hinzugefügt';


$string['module_global'] = 'Modulsichtbarkeit';
$string['module_global_help'] = 'Die Modulsichtbakeit gibt an, welche Umfragen angezeigt werden sollen.

**Global**<br>
Der Block zeigt alle Umfragen dieses Blocks im Kurs an, sowie alle Umfragen, die als «Global» markiert wurden, von allen anderen Modulen und Kursen.

**Nur für diesen Kurs**<br>
Der Block zeigt nur die Umfragen die in diesem Block erstellt wurden an. Die Umfragen können aber dennoch als «Global» markiert werden und so in anderen Modulen und Kursen erscheinen.';

$string['form:surveyurl'] = '«Link zur Umfrage»';
$string['form:surveyurl_help'] = 'Absolute URL zur externen Umfrage';
$string['form:subject'] = '«Titel»';
$string['form:subject_help'] = 'Der Titel wird als Linktext angezeigt.';
$string['form:description'] = '«Beschreibung»';
$string['form:description_help'] = 'Die Beschreibung wird dem Link hinzugefügt und ist auf der Linkseite ersichtlich.';
$string['form:expiry'] = '«Ablaufdatum und -zeit»';
$string['form:expiry_help'] = 'Nach dem Ablaufdatum ist die Umfrage auf Moodle nicht sichtbar. Diese Einstellung ist nicht an die Einstellungen der externen Umfrage gekoppelt.';
$string['form:points'] = '«Punkte»';
$string['form:points_help'] = 'Punkte, die vom Dozenten für die Beantwortung dieser Umfrage vorgesehen sind.';
$string['form:visibility'] = '«Sichtbarkeit»';
$string['form:visibility_help'] = 'Die Sichtbakeit gibt an, wo die Umrage angezeigt wird.

**Global**<br>
Die Umfrage wird im aktuellen Kurs, wie auch in allen Kursen die globale Umfragen zulassen, angezeigt.

**Nur für diesen Kurs**<br>
Die Umfrage wird nur in diesem Kurs angezeigt.';
