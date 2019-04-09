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
 * Report Lib
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
            "name" => $item->name,
        );
        array_push($response, $new_item);
    }

    return $response;
}

/**
 * Gets exportable info
 * @see get_data_report($data)
 * @param array $data
 * @return array Containing all reports
 */
function get_data_report($data, $course)
{
    foreach ($data as $element) {
        $consulta = "SELECT DISTINCT usuario.firstname as 'Nombre',
                    usuario.lastname as 'Apellidos',
                    usuario.username as 'Código',
                    usuario.email as 'Correo',
                    usuario.institution as 'Institución',
                    usuario.department as 'Facultad',
                    grupo.name as 'Grupo',
                    intento.state as 'Estado',
                    to_timestamp(intento.timestart) as 'Comenzado el',
                    to_timestamp(intento.timefinish) as 'Finalizando el',
                    justify_interval(to_timestamp(intento.timefinish)-to_timestamp(intento.timestart)) as 'Duracion',
                    grades.grade as 'Calificacion',
                    mod.grade as 'Nota Maxima'

                    FROM {quiz} mod INNER JOIN {quiz_attempts} intento ON intento.quiz = mod.id
                                    INNER JOIN {quiz_grades} grades ON grades.quiz = mod.id
                                    INNER JOIN {user} usuario ON usuario.id = intento.userid
                                    LEFT JOIN {groups_members} miembros ON miembros.userid = usuario.id
                                    LEFT JOIN {groups} grupo ON grupo.id = miembros.groupid
                    WHERE mod.id = $element AND grupo.courseid = $course AND grades.userid = intento.userid ";
    }
    return array("consulta");
}
