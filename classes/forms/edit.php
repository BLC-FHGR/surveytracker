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

require_once($CFG->libdir . '/formslib.php');

class edit_form extends \moodleform {

  public function definition() {
    global $CFG;
    $mform = $this->_form;

    $mform->addElement('header', 'general', get_string('surveytracker:editingasurvey', 'surveytracker'));

    $mform->addElement('hidden', 'moduleid', $this->_customdata->moduleid);
    $mform->setType('moduleid', PARAM_NOTAGS);

    $mform->addElement('hidden', 'surveyid', $this->_customdata->surveyid);
    $mform->setType('surveyid', PARAM_NOTAGS);

    $mform->addElement('text', 'surveyurl', get_string('edit:surveyurl', 'surveytracker'));
    $mform->setType('surveyurl', PARAM_NOTAGS);
    $mform->addHelpButton('surveyurl', 'form:surveyurl', 'surveytracker');

    $mform->addElement('text', 'subject', get_string('edit:subject', 'surveytracker'));
    $mform->setType('subject', PARAM_NOTAGS);
    $mform->addHelpButton('subject', 'form:subject', 'surveytracker');

    $mform->addElement('text', 'description', get_string('edit:description', 'surveytracker'));
    $mform->setType('description', PARAM_NOTAGS);
    $mform->addHelpButton('description', 'form:description', 'surveytracker');

    $mform->addElement('date_time_selector', 'expiry', get_string('edit:expiry', 'surveytracker'));
    $mform->setType('expiry', PARAM_NOTAGS);
    $mform->addHelpButton('expiry', 'form:expiry', 'surveytracker');

    $mform->addElement('float', 'points', get_string('edit:points', 'surveytracker'));
    $mform->setType('points', PARAM_NOTAGS);
    $mform->addHelpButton('points', 'form:points', 'surveytracker');

    $options = array(
      '' => get_string('choose').'...',
      '1' => get_string('surveytracker:moduleonly', 'surveytracker'),
      '2' => get_string('surveytracker:global', 'surveytracker'),
    );

    $mform->addElement('select', 'visibility', get_string('visibility', 'surveytracker'), $options);
    $mform->addHelpButton('visibility', 'form:visibility', 'surveytracker');

    $mform->addRule('surveyurl', get_string('required'), 'required', null, 'client');
    $mform->addRule('subject', get_string('required'), 'required', null, 'client');
    $mform->addRule('points', get_string('required'), 'required', null, 'client');
    $mform->addRule('visibility', get_string('required'), 'required', null, 'client');

    $this->add_action_buttons();

    $survey = $this->_customdata->survey;
    if(isset($survey) && isset($survey->surveyurl)) {
      $mform->setDefault('surveyurl', $survey->surveyurl);
      $mform->setDefault('subject', $survey->subject);
      $mform->setDefault('description', $survey->description);
      $mform->setDefault('expiry', date('U', $survey->expirydate));
      $mform->setDefault('points', $survey->points);
      $mform->setDefault('visibility', $survey->visibility);
    } else {
        $mform->setDefault('expiry', date('U', mktime(23, 59, 0)));
    }

    $styles = '<style>
    .mform {
      width: 830px;
      max-width: 100%;
      margin: 0 auto;
    }
    </style>';
    $mform->addElement('html', $styles);
  }
}
