<?php

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_surveytracker_mod_form extends moodleform_mod {
  public function definition()
  {
    $mform =& $this->_form;

    $mform->addElement('header', 'general', get_string('general', 'form'));

    $options = array(
      '' => get_string('choose').'...',
      '1' => get_string('surveytracker:moduleonly', 'surveytracker'),
      '2' => get_string('surveytracker:global', 'surveytracker'),
    );

    $mform->addElement('select', 'moduletype', get_string('modulevisibility', 'surveytracker'), $options);
    if ($this->current && isset($this->current->type)) {
      $mform->setDefault('moduletype', $this->current->type);
    }
    $mform->addRule('moduletype', get_string('required'), 'required', null, 'client');
    $mform->addHelpButton('moduletype', 'module_global', 'surveytracker');

    $this->standard_coursemodule_elements();
    $this->add_action_buttons();
  }
}
