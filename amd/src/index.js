/**
* index js management
* @module local_customreportuv
* @author Camilo José Cruz rivera
* @copyright 2019 Camilo José Cruz Rivera <cruz.camilo@correounivalle.edu.co>
* @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
define(['jquery', 'local_customreportuv/sweetalert', 'local_customreportuv/FileSaver'], function ($, swal) {
    return {
        init: function () {
            var checked = true;
            var data = [];
            var names = [];

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
                        names.push($(this).parent().text().trim().split("(")[0]);
                    }
                });
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
                            exportInfo(msg);
                        },
                        dataType: "json",
                        cache: "false",
                        error: function (msg) {
                            data = [];
                            swal('Server Error',
                                msg.error,
                                'error');
                            console.log("AJAXerror");
                            console.log(msg);
                        },
                    });
                }

            });

            function exportInfo(msg) {

                require(["xlsx"], function (XLSX) {
                    /* use XLSX here */
                    var wb = XLSX.utils.book_new();
                    var nameBook = $('#course').text();
                    var A = Date.now();
                    wb.Props = {
                        Title: nameBook,
                        Subject: "Reporte notas",
                        Author: "Campus Virtual Univalle",
                        CreatedDate: A
                    };
                    console.log(msg);
                    var i = 0;
                    msg.forEach(function (element) {
                        console.log(element);
                        var SheetName = names[i].substr(0, 31);
                        wb.SheetNames.push(SheetName);
                        if (element.length == 0) {
                            ws_data = [['No se encuentran calificaciones en este item']];
                            var ws = XLSX.utils.aoa_to_sheet(ws_data);
                        } else {
                            var ws = XLSX.utils.json_to_sheet(element);
                        }
                        wb.Sheets[SheetName] = ws;
                        console.log(wb);
                        i++;
                    });
                    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
                    var namefile = nameBook + ".xlsx";
                    saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), namefile);
                    data = [];
                    names = [];
                });
            }

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
                var view = new Uint8Array(buf);  //create uint8array as viewer
                for (var i = 0; i < s.length; i++) {
                    view[i] = s.charCodeAt(i) & 0xFF;
                } //convert to octet
                return buf;
            }
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