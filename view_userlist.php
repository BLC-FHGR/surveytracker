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
 require_once($CFG->dirroot.'/mod/surveytracker/lib.php');
 require_once($CFG->dirroot.'/mod/surveytracker/classes/participantlib.php');
 require_once($CFG->dirroot.'/mod/surveytracker/classes/surveylib.php');

 $id      = optional_param('id', 0, PARAM_INT); // Course Module ID
 $p       = optional_param('p', 0, PARAM_INT);  // Page instance ID

 if ($p) {
     if (!$surveytracker = $DB->get_record('surveytracker', array('id'=>$p))) {
         print_error('invalidaccessparameter');
     }
     $cm = get_coursemodule_from_instance('surveytracker', $surveytracker->id, $surveytracker->course, false, MUST_EXIST);

 } else {
     if (!$cm = get_coursemodule_from_id('surveytracker', $id)) {
         print_error('invalidcoursemodule');
     }
     $surveytracker = $DB->get_record('surveytracker', array('id'=>$cm->instance), '*', MUST_EXIST);
 }

 $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

 require_course_login($course, true, $cm);
 $context = context_course::instance($cm->course);
 require_capability('mod/surveytracker:viewresults', $context);
 require_capability('moodle/course:viewparticipants', $context);

 // page_view($page, $course, $cm, $context);

// $PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('userlist', 'surveytracker'));
$PAGE->set_heading(get_string('userlist', 'surveytracker'));
 $PAGE->set_url($CFG->wwwroot . '/mod/surveytracker/view_userlist.php', array('id' => $cm->id));

 echo $OUTPUT->header();

 $data = new stdClass();
 $data->currentmoduleid = $cm->instance;
 $data->cmid = $cm->id;
 $data->locale = array(
   'firstname' => get_string('table:firstname', 'surveytracker'),
   'lastname' => get_string('table:lastname', 'surveytracker'),
   'amount' => get_string('table:amount', 'surveytracker'),
   'surveys' => get_string('table:surveys', 'surveytracker'),
   'surveyparticipations' => get_string('table:surveyparticipations', 'surveytracker'),
   'surveyparticipationspoints' => get_string('table:surveyparticipationspoints', 'surveytracker'),
   'contestant' => get_string('table:contestant', 'surveytracker'),
   'contestantcount' => get_string('table:contestantcount', 'surveytracker'),
   'contestantpoints' => get_string('table:contestantpoints', 'surveytracker'),
   'export' => get_string('table:export', 'surveytracker'),
   'userlist' => get_string('participants'),
 );

 $data->coursemembers = array_values(participantlib::get_participants_by_course($course->id));

 echo $OUTPUT->render_from_template('mod_surveytracker/coursecontestantinfo', $data);
 echo $OUTPUT->footer();
