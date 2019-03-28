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
 * The Custom Report setup page.
 *
 * @package   customreportuv
 * @copyright  2019 Camilo José Cruz Rivera <cruz.camilo@correounivalle.edu.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../config.php';
require_once 'manager/report_lib.php';
require_once($CFG->libdir.'/adminlib.php');

$courseid = required_param('id', PARAM_INT);
$url = new moodle_url('/local/customgrader/index.php', array('id' => $courseid));

/// Make sure they can even access this course
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourseid');
}

require_login($course);

// $PAGE->set_url($url);
$PAGE->set_title("Reporte Univalle");

// $PAGE->set_pagelayout('admin');
//$extraurlparams no esta siendo recibido en el setting. Investigar causa
$extraurlparams = array('id' => $courseid);
admin_externalpage_setup('customreportuv','', $extraurlparams, "$CFG->wwwroot/local/customreportuv/index.php");
echo $OUTPUT->header();

echo $OUTPUT->heading("REPORTE EXPORTABLE");

$data = new stdClass();
$data->course = $course->fullname;
$data->items = get_info_course($courseid);

echo $OUTPUT->render_from_template('local_customreportuv/index', $data);

echo $OUTPUT->footer();
die;

