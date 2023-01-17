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
  require_once($CFG->dirroot . '/mod/surveytracker/classes/forms/edit.php');
  require_once($CFG->dirroot . '/mod/surveytracker/classes/surveylib.php');

  $mid      = optional_param('moduleid', 0, PARAM_INT); // Course Module ID
  $sid       = optional_param('surveyid', 0, PARAM_INT);  // Survey instance ID

  if (!$surveytracker = $DB->get_record('surveytracker', array('id' => $mid))) {
    print_error('invalidaccessparameter');
  }
  $cm = get_coursemodule_from_instance('surveytracker', $surveytracker->id, $surveytracker->course, false, MUST_EXIST);

  if ($sid) {
    if (!$survey = $DB->get_record('surveytracker_surveys', array('id' => $sid))) {
        print_error('invalidaccessparameter');
    }
  } else {
    $survey = new stdClass();
  }

  if ($survey && isset($survey->creatorid) && $survey->creatorid != $USER->id) {
    print_error('not permitted');
  }

  $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

  require_course_login($course, true, $cm);
  $context = context_module::instance($cm->id);
  require_capability('mod/surveytracker:view', $context);

  // page_view($page, $course, $cm, $context);
  $PAGE->set_url($CFG->wwwroot . '/mod/surveytracker/view_edit.php', array('moduleid' => $mid, 'surveyid' => $sid));
  $PAGE->set_title(get_string('surveytracker:editingasurvey', 'surveytracker'));

  $customdata = new stdClass();
  $customdata->moduleid = $mid;
  $customdata->surveyid = $sid;
  $customdata->survey = $survey;
  $myform = new edit_form($PAGE->url . '', $customdata);

  if ($myform->is_cancelled()) {
    redirect($CFG->wwwroot . '/mod/surveytracker/view.php?id=' . $cm->id);
  } else if ($data = $myform->get_data()) {
    if ($data->surveyid) {
      surveylib::update_instance($data);
      redirect($CFG->wwwroot . '/mod/surveytracker/view.php?id=' . $cm->id, get_string('surveyedited', 'surveytracker'));
    } else {
      surveylib::add_instance($data);
      redirect($CFG->wwwroot . '/mod/surveytracker/view.php?id=' . $cm->id, get_string('surveyadded', 'surveytracker'));
    }
  }

  echo $OUTPUT->header();
  $myform->display();
  echo $OUTPUT->footer();
