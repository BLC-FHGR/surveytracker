<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Survey Tracker mod
 *
 * @package    mod_surveytracker
 * @copyright  Copyright (c) 2022 Open LMS (https://www.openlms.net/)
 * @author     FHGR - Marc-Alexander Iten
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Survey Tracker';
$string['modulenameplural'] = 'Survey Trackers';
$string['modulename_help'] = 'With the survey tracker a student can spread a survey and other students can participate. The students will get the provided amount of points on their account.';
$string['modulename_link'] = 'mod/surveytracker/view';
$string['pluginname'] = 'Survey Tracker';
$string['pluginadministration'] = 'Survey Tracker administration';
$string['surveytracker'] = 'Survey Tracker';
$string['userlist'] = 'userlist';
$string['showuserlist'] = 'show userlist';

$string['surveytracker:addinstance'] = 'Add a new Survey Tracker mod';
$string['surveytracker:myaddinstance'] = 'Add a new Survey Tracker mod';
$string['surveytracker:view'] = 'See Survey Tracker';
$string['surveytracker:editingasurvey'] = 'Edit Survey';
$string['privacy:metadata'] = 'Survey Tracker mod does not store data itself.';
$string['surveyrunning'] = 'Survey is ongoing.';
$string['surveysolved'] = 'Survey successfully solved.';
$string['surveysolvedearlier'] = 'Survey was solved earlier.';

$string['modulevisibility'] = 'Module visibility';
$string['visibility'] = 'Visibility';
$string['surveytracker:global'] = 'Global';
$string['surveytracker:moduleonly'] = 'This course only';

$string['table:survey'] = 'Survey';
$string['table:points'] = 'Points';
$string['table:expires'] = 'Expires';
$string['table:add'] = 'add survey';
$string['table:edit'] = 'edit';
$string['table:participants'] = 'Participants';
$string['table:clicktocopy'] = 'click to copy';
$string['table:copied'] = 'copied';
$string['table:firstname'] = 'Prename';
$string['table:lastname'] = 'Name';
$string['table:amount'] = 'Amount';
$string['table:surveys'] = 'Created Surveys';
$string['table:surveyparticipations'] = 'Participations';
$string['table:surveyparticipationspoints'] = 'Points';
$string['table:contestant'] = 'Participated';
$string['table:contestantcount'] = 'Contestant';
$string['table:contestantpoints'] = 'Points';
$string['table:export'] = 'Export as .xlsx';

$string['edit:surveyurl'] = 'Link to the survey';
$string['edit:subject'] = 'Title';
$string['edit:description'] = 'Description';
$string['edit:expiry'] = 'Expiry Date and Time';
$string['edit:points'] = 'Points';
$string['surveyedited'] = 'Survey edited';
$string['surveyadded'] = 'Survey added';

$string['module_global'] = 'module visibility';
$string['module_global_help'] = 'If you choose «global» the module block will show all surveys from this block as well as all surveys marked as «global» from other blocks.

With the option «This course only» only survey added in this course will be shown. The survey may also be «global» and be shown in other «global» module blocks.';

$string['form:surveyurl'] = '«Link to the survey»';
$string['form:surveyurl_help'] = 'Paste the link of the survey here.';
$string['form:subject'] = '«Title»';
$string['form:subject_help'] = 'Add a title. It will be shown as link in the list.';
$string['form:description'] = '«Description»';
$string['form:description_help'] = 'Give some more information about the survey.';
$string['form:expiry'] = '«Expiry Date and Time»';
$string['form:expiry_help'] = 'Add a expiry date. All sruveys will be sorted by expiry date and will disapear at the expiry date.';
$string['form:points'] = '«Points»';
$string['form:points_help'] = 'Add the points given for your survey.';
$string['form:visibility'] = '«Visibility»';
$string['form:visibility_help'] = 'If you choose «global» the survey will be shown in this course as well as in every other course with a survey module.

With the option «this course only» the survey will only be shown in the current course.';
