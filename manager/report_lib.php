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
 * Grader Lib
 *
 * @author     Camilo José Cruz Rivera
 * @package    customreportuv
 * @copyright  2019 Camilo José Cruz Rivera <cruz.camilo@correounivalle.edu.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Queries from module custom report UV

/**
 * Gets course information given its id
 * @see get_info_course($id_curso)
 * @param $id_curso --> course id
 * @return array Containing all calificable test report
 */

function get_info_course($id_curso)
{
    global $DB;

    $query = "SELECT id, name FROM {quiz} WHERE course = $id_curso";
    $response_query = $DB->get_records_sql($query);
    $response = array();
    //Processing data to allow template to process
    foreach ($response_query as $item) {
        $new_item = array(
            "id" => $item->id,
            "name" => $item->name
        );
        array_push($response, $new_item);
    }

    return $response;
}
