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
 $context = context_module::instance($cm->id);
 require_capability('mod/surveytracker:view', $context);

 // page_view($page, $course, $cm, $context);
 $PAGE->set_url('/mod/surveytracker/view.php', array('id' => $cm->id));

 $coursenode = $PAGE->navigation->find($course->id, navigation_node::TYPE_COURSE);
 $menunode = $coursenode->add(
     get_string('userlist', 'surveytracker'),
     new moodle_url('/mod/surveytracker/view_userlist.php', array('id' => $cm->id))
 );
 $menunode->make_active();

 echo $OUTPUT->header();

 $data = new stdClass();
 $data->currentmoduleid = $cm->instance;
 $data->cmid = $cm->id;
 $data->surveys = array();
 $data->locale = array(
   'survey' => get_string('table:survey', 'surveytracker'),
   'points' => get_string('table:points', 'surveytracker'),
   'add' => get_string('table:add', 'surveytracker'),
   'edit' => get_string('table:edit', 'surveytracker'),
   'expires' => get_string('table:expires', 'surveytracker'),
   'expired' => get_string('table:expired', 'surveytracker'),
   'participants' => get_string('table:participants', 'surveytracker'),
   'clicktocopy' => get_string('table:clicktocopy', 'surveytracker'),
   'copied' => get_string('table:copied', 'surveytracker'),
   'showuserlist' => get_string('showuserlist', 'surveytracker'),
   'userlist' => get_string('participants'),
 );

 $coursecontext = context_course::instance($cm->course);
 if (has_capability('mod/surveytracker:viewresults', $coursecontext)) {
   $data->userlistlink = $CFG->wwwroot . '/mod/surveytracker/view_userlist.php?id=' . $cm->id;
 }

 $is_global = ($surveytracker->type == 2 ? ' OR visibility = 2' : '');

 $surveys_new = $DB->get_records_sql(
   'SELECT * FROM {surveytracker_surveys} WHERE (moduleid = :moduleid' . $is_global . ') AND expirydate > :expirydate ORDER BY expirydate ASC;',
   [
     'moduleid' => $cm->instance,
     'expirydate' => time(),
   ]
 );
$surveys_old = $DB->get_records_sql(
    'SELECT * FROM {surveytracker_surveys} WHERE (moduleid = :moduleid' . $is_global . ') AND expirydate <= :expirydate ORDER BY expirydate DESC;',
    [
        'moduleid' => $cm->instance,
        'expirydate' => time(),
    ]
);
foreach($surveys_new as $survey) {
    $survey->editable = $USER->id == $survey->creatorid;
    $survey->user = $USER->id;
    $survey->participated = participantlib::has_participated($survey->id);
    if ($survey->editable) {
        $survey->participants = participantlib::count_by_surveyid($survey->id);
        $survey->redirecturl = $CFG->wwwroot . '/mod/surveytracker/surveyreferrer.php?STmid=' . $cm->instance . '&STsid=' . $survey->id;
    }
    $survey->active = true;
    $data->surveys[] = $survey;
}
foreach($surveys_old as $survey) {
    $survey->editable = false;
    $survey->user = $USER->id;
    $survey->participated = participantlib::has_participated($survey->id);
    if ($USER->id == $survey->creatorid) {
        $survey->participants = participantlib::count_by_surveyid($survey->id);
    }
    $survey->active = false;
    $data->surveys[] = $survey;
}

 echo $OUTPUT->render_from_template('mod_surveytracker/coursemoduleinfo', $data);
 echo $OUTPUT->footer();
