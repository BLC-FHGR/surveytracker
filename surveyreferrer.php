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

$sid = optional_param('STsid', 0, PARAM_INT);  // Survey instance ID

// Umfrage-Teilnehmer laden
if (!$participant = participantlib::get_instance_by_surveyid($sid)) {
    print_error('invalidaccessparameter');
}

// Umfrage-Block laden
if (!$surveytracker = $DB->get_record('surveytracker', array('id' => $participant->moduleid))) {
  print_error('invalidaccessparameter');
}
$cm = get_coursemodule_from_instance('surveytracker', $surveytracker->id, $surveytracker->course, false, MUST_EXIST);

// Modul laden
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

// redirect, falls bereits teilgenommen
if ($participant->enddate > 0) {
  redirect('/mod/surveytracker/view.php?id=' . $cm->id, get_string('surveysolvedearlier', 'surveytracker'));
}

// Kurs-Berechtigung prüfen
require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
// Umfrage-Berechtigung prüfen
require_capability('mod/surveytracker:view', $context);

// Umfrage laden
if (!$survey = $DB->get_record('surveytracker_surveys', array('id' => $sid))) {
    print_error('invalidaccessparameter');
}

// Umfragen-Teilnahme updaten
$participant = participantlib::update_instance($participant);

if (!$participant) {
  print_error('dbupdate');
}
redirect('/mod/surveytracker/view.php?id=' . $cm->id, get_string('surveysolved', 'surveytracker'));
