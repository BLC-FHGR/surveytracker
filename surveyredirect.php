<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Survey Tracker overview mod.
 *
 * @package    mod_surveytracker
 * @copyright  Copyright (c) 2022 Open LMS (https://www.openlms.net/)
 * @author     FHGR - Marc-Alexander Iten
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/surveytracker/classes/participantlib.php');

$mid = optional_param('moduleid', 0, PARAM_INT);  // Module instance ID
$sid = optional_param('surveyid', 0, PARAM_INT);  // Survey instance ID

// Umfrage-Block aus der DB lesen
if (!$surveytracker = $DB->get_record('surveytracker', array('id' => $mid))) {
  print_error('invalidaccessparameter');
}
$cm = get_coursemodule_from_instance('surveytracker', $surveytracker->id, $surveytracker->course, false, MUST_EXIST);

// Modul laden
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

// prüfen der Berechtigung für Modul
require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/surveytracker:view', $context);


// Umfrage von der DB holen
if (!$survey = $DB->get_record('surveytracker_surveys', array('id' => $sid))) {
    print_error('invalidaccessparameter');
}

// neue Teilnahme erstellen
$participant = participantlib::create_instance($cm->instance, $survey->id, $survey->points);
if ($USER->id === $survey->creatorid) {
  $participant->points = 0;
}
// neue Teilnahme hinzufügen
$pid = participantlib::add_instance($participant);

// wenn Umfrage noch nicht abgeschlossen
if ($pid === 0) {
  redirect($_SERVER['HTTP_REFERER'], get_string('surveyrunning', 'surveytracker'));
} else if ($pid === -1) { // wenn Umfrage früher abgeschlossen
  redirect($_SERVER['HTTP_REFERER'], get_string('surveysolvedearlier', 'surveytracker'));
} else { // zur Umfrage
  header('Location: ' . $survey->surveyurl . (strpos($survey->surveyurl, '?') > 0 ? '&' : '?') . 'STpid=' . $pid );
}
die();
