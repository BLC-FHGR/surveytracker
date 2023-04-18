<?php

class participantlib {

  static function create_instance($moduleid, $surveyid, $points) {
    global $USER;

    $result = new stdClass();

    $result->moduleid = $moduleid;
    $result->surveyid = $surveyid;
    $result->participantid = $USER->id;
    $result->startdate = time();
    $result->enddate = null;
    $result->points = $points;

    return $result;
  }

  static function add_instance($data) {
    global $DB, $USER;

    print_r($data);

    if ($existing = $DB->get_record('surveytracker_participants', array(
      'surveyid' => $data->surveyid,
      'participantid' => $data->participantid,
      'enddate' => null,
    ))) {
      return $existing->id;
    }

    if ($existing = $DB->get_records('surveytracker_participants', array(
      'surveyid' => $data->surveyid,
      'participantid' => $data->participantid
    ))) {
      return -1;
    }

    $data->startdate = time();
    $id = $DB->insert_record('surveytracker_participants', $data);

    return $id;
  }

  static function update_instance($data) {
    global $DB;

    if (!$participant = $DB->get_record('surveytracker_participants', array('id' => $data->id))) {
        return false;
    }

    $participant->enddate = time();

    return $DB->update_record('surveytracker_participants', $participant);
  }

  static function delete_instance($id) {
    global $DB;

    if (!$participant = $DB->get_record('surveytracker_participants', array('id' => $id))) {
        return false;
    }

    $result = true;

    if (!$DB->delete_records('surveytracker_participants', array("id" => $participant->id))) {
        $result = false;
    }

    return $result;
  }

  static function has_participated($surveyid) {
    global $DB, $USER;

    $records = $DB->get_record_sql('SELECT COUNT(enddate) as count FROM {surveytracker_participants} WHERE surveyid = :surveyid AND participantid = :participantid AND enddate IS NOT NULL;',
      [
          'surveyid' => $surveyid,
          'participantid' => $USER->id
      ]
    );
    return $records->count;
  }

  static function count_by_surveyid($surveyid) {
    global $DB;

    $records = $DB->get_record_sql('SELECT COUNT(enddate) as count FROM {surveytracker_participants} WHERE surveyid = :surveyid AND enddate IS NOT NULL;',
        [
            'surveyid' => $surveyid
        ]
    );
    return $records->count;
  }

  static function sum_points_by_surveyid($surveyid) {
    global $DB;

    return $DB->get_records_sql('SELECT SUM(points) FROM {surveytracker_participants} WHERE surveyid = :surveyid AND enddate IS NOT NULL;',
    [
      'surveyid' => $surveyid
    ]
  );
  }

  static function get_participations_of_user() {
    global $DB, $USER;

    return $DB->get_record_sql(
      'SELECT COUNT(id) AS contestantcount,
      SUM(points) AS contestantpoints
      FROM {surveytracker_participants} WHERE participantid = :userid AND enddate IS NOT NULL;',
      [
        'userid' => $USER->id,
      ]
    );
  }

  static function get_surveyinfos_of_user() {
    global $DB, $USER;

    return $DB->get_record_sql(
      'SELECT crs.id,
      COUNT(crs.id) AS surveys,
      SUM(crp.crpcount) AS surveyparticipations,
      SUM(crp.crpsum) AS surveyparticipationspoints
      FROM {surveytracker_surveys} crs
        LEFT JOIN (
          SELECT surveyid, COUNT(crp.id) AS crpcount, SUM(crp.points) AS crpsum
          FROM {surveytracker_participants} crp
          WHERE crp.enddate IS NOT NULL
          GROUP BY crp.surveyid
        ) AS crp
        ON crp.surveyid = crs.id
      WHERE crs.creatorid = :userid
      GROUP BY crs.creatorid;',
      [
        'userid' => $USER->id,
      ]
    );
  }

  static function get_participants_by_course($courseid) {
    global $DB;

    return $DB->get_records_sql(
      'SELECT usr.id, usr.username, usr.firstname, usr.lastname,
      COUNT(crs.id) AS surveys,
      SUM(crp.crpcount) AS surveyparticipations,
      SUM(crp.crpsum) AS surveyparticipationspoints,
      cop.copcount AS contestantcount,
      cop.copsum AS contestantpoints
      FROM {enrol}
        INNER JOIN ({user_enrolments}
         INNER JOIN ({user} usr
           LEFT JOIN ({surveytracker_surveys} crs
              LEFT JOIN (
                SELECT surveyid, COUNT(crp.id) AS crpcount, SUM(crp.points) AS crpsum
                FROM {surveytracker_participants} crp
                WHERE crp.enddate IS NOT NULL
                GROUP BY crp.surveyid
              ) AS crp
              ON crp.surveyid = crs.id
             )
           ON crs.creatorid = usr.id

            LEFT JOIN (
              SELECT participantid, COUNT(cop.id) AS copcount, SUM(cop.points) AS copsum
              FROM {surveytracker_participants} cop
              WHERE cop.enddate IS NOT NULL
              GROUP BY cop.participantid
            ) AS cop
            ON cop.participantid = usr.id

         ) ON usr.id = {user_enrolments}.userid
       ) ON {user_enrolments}.enrolid = {enrol}.id
      WHERE {enrol}.courseid = :courseid
      GROUP BY usr.id
      ORDER BY usr.lastname ASC, usr.firstname ASC;',
      [
        'courseid' => $courseid,
      ]
    );
  }
}
