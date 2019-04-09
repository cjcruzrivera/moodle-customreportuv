/**
* index js management
* @module local_customreportuv
* @author Camilo José Cruz rivera
* @copyright 2019 Camilo José Cruz Rivera <cruz.camilo@correounivalle.edu.co>
* @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
define(['jquery', 'local_customreportuv/sweetalert'], function ($, swal) {
    return {
        init: function () {
            var checked = true;
            var data = [];

            $('#sel').click(function () {
                $('[type="checkbox"]').each(function () {
                    if (!checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                checked = !checked;
                data = [];
            });

            $('#download').click(function () {
                $('[type="checkbox"]').each(function () {
                    if ($(this).prop("checked")) {
                        data.push($(this).val());
                    }
                });
                console.log(data)
                if (data.length == 0) {
                    swal("Error", "Seleccione al menos un item de calificacion a exportar", "warning");
                } else {
                    $.ajax({
                        type: "POST",
                        data: {
                            data: data,
                            course: getCourseid()
                        },
                        url: "managers/ajax_processing.php",
                        success: function (msg) {
                            console.log(msg);
                            alert("EXPORTADO");
                            data = []
                        },
                        dataType: "json",
                        cache: "false",
                        error: function (msg) {
                            swal('Server Error',
                                msg.error,
                                'error');
                            console.log("AJAXerror");
                            console.log(msg);
                        },
                    });
                }

            });

            /**
            * @method getCourseid
            * @desc Returns the id of the course obtained from the url
            * @return {int} course_id
            */
            function getCourseid() {
                var informacionUrl = window.location.search.split("=");
                for (var i = 0; i < informacionUrl.length; i += 2) {
                    if (informacionUrl[i] == "?id") {
                        var curso = informacionUrl[i + 1];
                    }
                }
                return curso;
            }
        }
    };
});