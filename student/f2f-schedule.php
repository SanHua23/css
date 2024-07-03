<?php include "../connect.php"; ?>

<!DOCTYPE html>
<html>
<!-- header link -->
<?php include "plugins-header.php"; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
<!-- JS for jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- JS for full calender -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="../plugins/bootstrap5/bootstrap.min.css">
<body>
    <div class="main-div">
        <!-- sidebar and topbar start -->
        <?php include "sidebar.php"; ?>
        <!-- sidebar and topbar start -->

        <!-- content container start -->
        <div class="content-div p-4">
            <div class="row row-gap-3">
                <div class="col-12">
                    <div class="border bg-white d-flex flex-column row-gap-2 p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <strong class="mb-2">List of F2F Schedule</strong>
                            <div>
                                <input type="text" id="search-bar" class="form-control form-control-sm rounded-1" placeholder="Search here">
                            </div>
                        </div>
                        
                        <div style="max-height: 70vh; overflow-y: auto;">
                            <table id="schedule_table" class="table table-bordered table-striped">
                                <thead class="position-sticky top-0">
                                    <tr class="py-5">
                                        <th>#</th>
                                        <th>Time & Date</th>
                                        <th>Subject</th>
                                        <th>Year & Section</th>
                                        <th>Room</th>
                                        <th>Course</th>
                                        <th>Teacher</th>
                                        <!-- <th class="col-auto text-center">Action</th> -->
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM ol_schedule_tbl sch 
                                                INNER JOIN course_tbl c ON c.course_id = sch.course_id
                                                INNER JOIN section_tbl sec ON sec.section_id = sch.section_id
                                                INNER JOIN room_tbl r ON r.room_id = sch.room_id
                                                LEFT JOIN faculty_tbl f ON f.faculty_id = sch.faculty_id
                                                INNER JOIN subject_tbl sub ON sub.subject_id = sch.subject_id";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        $row_count = 1;
                                        if ($rows) {
                                            foreach ($rows as $row):
                                    ?>
                                    <tr data-id="<?php echo $row['ol_schedule_id']; ?>">
                                        <td width="50"><?php echo $row_count++?></td>
                                        <td>
                                            <?php echo date("F d, Y h:i a", strtotime($row['start_datetime'])); ?><br><?php echo date("F d, Y h:i a", strtotime($row['end_datetime'])); ?>
                                            
                                        </td>
                                        <td><?php echo $row['subject_title']; ?></td>
                                        <td><?php echo $row['year_level']."-".$row['section']; ?></td>
                                        <td><?php echo $row['room_number']; ?></td>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td>
                                            <?php
                                                if ($row['faculty_id'] === null) {
                                                    echo "No Teacher Assigned";
                                                } else {
                                                    echo $row['first_name'].' '.$row['last_name'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; } ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border bg-white p-3 mt-2">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>


        </div>
        <!-- content container end-->


        <!-- Event Details Modal -->
        <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-0">
                    <div class="modal-header rounded-0">
                        <h5 class="modal-title">Schedule Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body rounded-0">
                        <div class="container-fluid">
                            <div class="d-flex flex-column row-gap-1">
                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Course:</dt>
                                    <span id="modal-course" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Year & Section:</dt>
                                    <span id="modal-ys" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Teacher:</dt>
                                    <span id="modal-fn" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Subject:</dt>
                                    <span id="modal-subject" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Room:</dt>
                                    <span id="modal-room" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Start:</dt>
                                    <span id="modal-start" class="fw-bold"></span>
                                </div>


                                <div class="d-flex align-items-center">
                                    <dt class="text-muted me-2">End:</dt>
                                    <span id="modal-end" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted me-2">Remarks:</dt>
                                    <span id="modal-remarks" class="fw-bold badge"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer rounded-0">
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Event Details Modal -->

    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
    <!-- js -->
    <!-- BOOTSTRAP 5 JS -->
    <script type="text/javascript" src="../plugins/bootstrap5/bootstrap.min.js"></script>
    <!-- FONT AWESOME OFFLINE -->
    <script src="../plugins/fontawesome/all.min.js" crossorigin="anonymous"></script>
    <!-- sweetalert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- custom js -->
    <script src="../assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
    display_events(); // Initial display
    
    // Function to fetch and display events
    function display_events() {
        var events = [];
        $.ajax({
            url: 'display_event.php',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                var result = response.data;
                $.each(result, function(i, item) {
                    events.push({
                        event_id: item.event_id,
                        title: item.subject_title,
                        start: item.start_datetime,
                        end: item.end_datetime,
                        color: item.color,
                        course_name: item.course_name,
                        subject_title: item.subject_title,
                        ys: item.ys,
                        faculty_name: item.faculty_name,
                        remarks: item.remarks,
                        room: item.room
                    });
                });

                // Destroy the previous calendar instance if exists
                $('#calendar').fullCalendar('destroy');

                // Initialize or reinitialize fullCalendar
                $('#calendar').fullCalendar({
                    defaultView: 'month',
                    timeZone: 'local',
                    editable: true,
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end) {
                        // Handle select event if needed
                    },
                    events: events,
                    eventRender: function(event, element, view) {
                        element.bind('click', function() {
                            var _details = $('#event-details-modal');
                            var id = event.event_id;

                            if (id) {
                                // Format start and end times using moment.js (assuming moment.js is included)
                                var formattedStart = moment(event.start).format('MMMM DD, YYYY h:mm A');
                                var formattedEnd = moment(event.end).format('MMMM DD, YYYY h:mm A');

                                _details.find('#modal-remarks').text(event.remarks);
                                _details.find('#modal-fn').text(event.faculty_name);
                                _details.find('#modal-ys').text(event.ys);
                                _details.find('#modal-room').text(event.room);
                                _details.find('#modal-course').text(event.course_name);
                                _details.find('#modal-subject').text(event.subject_title);
                                _details.find('#modal-start').text(formattedStart);
                                _details.find('#modal-end').text(formattedEnd);
                                _details.find('.delete_data').attr('data-id', id);
                                _details.modal('show');

                                if (event.remarks === "cancelled") {
                                    $('#modal-remarks').addClass('text-bg-danger')
                                }else if (event.remarks === "approved") {
                                    $('#modal-remarks').addClass('text-bg-success')
                                }else{
                                    $('#modal-remarks').addClass('text-bg-secondary')
                                }
                            }
                        });
                    }
                }); // End fullCalendar initialization

                // Refresh data every 5 seconds
                // setTimeout(display_events, 5000); // 5000 milliseconds = 5 seconds
            },
            error: function(xhr, status) {
                alert('Error fetching events: ' + xhr.statusText);
            }
        }); // End AJAX request
    }
});


    </script>


</body>
</html>


