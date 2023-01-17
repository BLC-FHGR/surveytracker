<?php

class surveylib {

    static function add_instance($data) {
        global $DB, $USER;

        print_r($data);

        $data->expirydate = $data->expiry;
        $data->creatorid = $USER->id;
        $data->createdate = time();
        $id = $DB->insert_record('surveytracker_surveys', $data);

        return $id;
    }

    static function update_instance($data) {
        global $DB;

        if (!$survey = $DB->get_record('surveytracker_surveys', array('id' => $data->surveyid))) {
            return false;
        }

        $survey->surveyurl = $data->surveyurl;
        $survey->subject = $data->subject;
        $survey->description = $data->description;
        $survey->expirydate = $data->expiry;
        $survey->points = $data->points;
        $survey->visibility = $data->visibility;

        return $DB->update_record('surveytracker_surveys', $survey);
    }

    static function delete_instance($id) {
        global $DB;

        if (!$survey = $DB->get_record('surveytracker_surveys', array('id' => $id))) {
            return false;
        }

        $result = true;

        if (!$DB->delete_records('surveytracker_surveys', array("id"=>$survey->id))) {
            $result = false;
        }

        return $result;
    }

    static function get_surveys_by_creator($userid) {
      global $DB;

      return $DB->get_records('surveytracker_surveys', array('creatorid' => $userid));
    }

    static function get_surveyids_by_creator($userid) {
      $surveys = self::get_surveys_by_creator($userid);
      return array_column($surveys, 'id');
    }
}
