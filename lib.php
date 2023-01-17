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

 defined('MOODLE_INTERNAL') || die;
 require_once($CFG->dirroot.'/mod/surveytracker/classes/participantlib.php');

 function mod_surveytracker_before_footer()
 {
   //\core\notification::info('testmessage');
 }

/**
 * Survey Tracker overview mod.
 *
 * @package    mod_surveytracker
 * @copyright  Copyright (c) 2022 Open LMS (https://www.openlms.net/)
 * @author     FHGR - Marc-Alexander Iten
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/** SURVEYTRACKER_MAX_NAME_LENGTH = 50 */
define("SURVEYTRACKER_MAX_NAME_LENGTH", 50);

/**
 * @uses SURVEYTRACKER_MAX_NAME_LENGTH
 * @param object $label
 * @return string
 */
function get_surveytracker_name($surveytracker) {
    $name = get_string('modulename','surveytracker');
    if (core_text::strlen($name) > SURVEYTRACKER_MAX_NAME_LENGTH) {
        $name = core_text::substr($name, 0, SURVEYTRACKER_MAX_NAME_LENGTH)."...";
    }

    if (empty($name)) {
        // arbitrary name
        $name = get_string('modulename','surveytracker');
    }

    return $name;
}

function surveytracker_add_instance($data, $mform = null) {
    global $DB;

    $data->name = get_surveytracker_name($data);
    $data->type = $data->moduletype;
    $data->timemodified = time();

    $id = $DB->insert_record('surveytracker', $data);

    $completiontimeexpected = !empty($data->completionexpected) ? $data->completionexpected : null;
    \core_completion\api::update_completion_date_event($data->coursemodule, 'surveytracker', $id, $completiontimeexpected);

    return $id;
}

function surveytracker_update_instance($data, $mform) {
    global $DB;

    if (!$surveytracker = $DB->get_record('surveytracker', array('id' => $data->instance))) {
        return false;
    }

    $surveytracker->name = get_surveytracker_name($data);
    $surveytracker->course = $data->course;
    $surveytracker->type = $data->moduletype;
    $surveytracker->timemodified = time();

    $completiontimeexpected = !empty($data->completionexpected) ? $data->completionexpected : null;
    \core_completion\api::update_completion_date_event($data->coursemodule, 'surveytracker', $data->instance, $completiontimeexpected);

    return $DB->update_record('surveytracker', $surveytracker);
}

function surveytracker_delete_instance($id) {
    global $DB;

    if (!$surveytracker = $DB->get_record('surveytracker', array("id"=>$id))) {
        return false;
    }

    $cm = get_coursemodule_from_instance('surveytracker', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'surveytracker', $id, null);

    $result = true;

    if (! $DB->delete_records('surveytracker', array("id"=>$surveytracker->id))) {
        $result = false;
    }

    return $result;
}

function surveytracker_cm_info_view($cm) {
  global $DB, $CFG;

  if (!$surveytracker = $DB->get_record('surveytracker', array('id' => $cm->instance))) {
      return null;
  }

  $is_global = ($surveytracker->type == 2 ? ' OR visibility = 2' : '');
  $surveys = $DB->get_records_sql(
    'SELECT * FROM {surveytracker_surveys} WHERE (moduleid = :moduleid' . $is_global . ') AND expirydate > :expirydate ORDER BY expirydate ASC;',
    [
      'moduleid' => $cm->instance,
      'expirydate' => time(),
    ]
  );


  $content = '<table id="surveytrackertable">';
  $content .= '<thead><tr><th></th><th>'.get_string('table:survey', 'surveytracker').'</th><th>'.get_string('table:points', 'surveytracker').'</th></tr></thead>';
  $content .= '<tbody>';

  foreach($surveys as $survey) {
    $content .= '<tr>';
    $content .= '<td style="font-size: .5em;">';
    if (participantlib::has_participated($survey->id)) {$content .= '✅ ';} else { $content .= '▶️ '; }
    $content .= '</td><td>';
    $content .= '<a href="/mod/surveytracker/surveyredirect.php?moduleid=' . $cm->instance . '&surveyid=' . $survey->id . '">';
    $content .= $survey->subject;
    $content .= '</a>';
    $content .= '</td>';
    $content .= '<td>' . $survey->points . '</td>';
    $content .= '</tr>';
  }

  $content .= '</tbody>';
  $content .= '</table>';
  $content .= '<style>';
  $content .= '#surveytrackertable { width: 100%; }';
  $content .= '#surveytrackertable tbody tr:hover { background-color: rgba(0,0,0,0.05); }';
  $content .= '#surveytrackertable th:first-child { width: 1em; }';
  $content .= '#surveytrackertable th:last-child, #surveytrackertable td:last-child { text-align: right; }';
  $content .= '</style>';

  $context = context_course::instance($cm->course);
  if (has_capability('mod/surveytracker:viewresults', $context)) {
    $content .= '<hr><a href="' . $CFG->wwwroot . '/mod/surveytracker/view_userlist.php?id=' . $cm->id . '">» ' . get_string('course:viewparticipants', 'role') . '</a>';
  }

  $cm->set_content($content);
}

