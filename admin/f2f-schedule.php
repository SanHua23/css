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
<!-- bootstrap css and js -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<body>
    <div class="main-div">
        <!-- sidebar and topbar start -->
        <?php include "sidebar.php"; ?>
        <!-- sidebar and topbar start -->

        <!-- content container start -->
        <div class="content-div p-4">
            <div class="row row-gap-3">
                <div class="col-12 col-lg-4">
                    <!-- inserting -->

                    <form id="add_ol_schedule" class="border d-flex flex-column row-gap-2 bg-white p-3" required>
                        <div class="mb-2 d-flex align-items-center justify-content-between">
                            <strong class="form-title">Set F2F Schedule</strong>
                            <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
                        </div>
                        <input type="text" name="section_id" id="section_id" class="form-control rounded-1" value="0" hidden>
                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="course_id" id="course_id_select">
                                <option hidden selected value="">Choose Option Below</option>
                                <?php
                                // PHP code to fetch courses from database
                                $sql = "SELECT * FROM course_tbl";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($rows) {
                                    foreach ($rows as $row):
                                        ?>
                                        <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                                    <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                             <label for="course_id">Course Name:</label>
                        </div>

                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="year_level" id="year_level_select" disabled>
                                <option hidden selected value="">Choose Option Below</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                            <label for="year_level">Year Level:</label>
                        </div>

                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="section_id" id="section_id_select" disabled>
                                <option hidden selected value="">Choose Option Below</option>
                            </select>
                            <label for="section_id">Year and Section:</label>
                        </div>  

                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="subject_id" id="subject_id_select" disabled>
                                <option hidden selected value="">Choose Option Below</option>
                            </select>
                            <label for="subject_id">Subject:</label>
                        </div>
                        
                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="room_id" id="room_id">
                                <option hidden selected value="">Choose Option Below</option>
                                <?php
                                // PHP code to fetch courses from database
                                $sql = "SELECT * FROM room_tbl";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($rows) {
                                    foreach ($rows as $row):
                                        ?>
                                        <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_number']; ?></option>
                                    <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                             <label for="room_id">Room:</label>
                        </div>

                        <div class="form-floating">
                            <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control rounded-1" placeholder="start time" required>
                            <label for="start_datetime">Start Time:</label>
                        </div>
                        <div class="form-floating">
                            <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control rounded-1" placeholder="start time" required>
                            <label for="end_datetime">End Time:</label>
                        </div>

                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="semester" id="semester">
                                <option hidden selected value="">Choose Option Below</option>
                                <option value="1">1st semester</option>
                                <option value="2">2nd semester</option>
                            </select>
                            <label for="semester">Semester:</label>
                        </div>
                        <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
                    </form>



                    <div class="border bg-white p-3 mt-2">
                        <div id="calendar"></div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 d-flex flex-column row-gap-2">
                    <div class="border bg-white d-flex flex-column row-gap-1 p-3">
                        <strong class="mb-2">Prefered Subjects:</strong>
                        <div id="ps-container" class="row">
                            <small>Please choose faculty teacher to view</small>
                        </div>
                    </div>
                    <div class="border bg-white d-flex flex-column row-gap-2 p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <strong class="mb-2">List of Faculty Teacher</strong>
                            <div>
                                <input type="text" id="search-bar" class="form-control form-control-sm form-control form-control-sm-sm rounded-1 mb-3" placeholder="Search here">
                            </div>
                        </div>
                        
                        <div style="max-height: 70vh; overflow-y: auto;">
                            <table id="faculty_table" class="table table-bordered table-striped">
                                <thead class="position-sticky top-0">
                                    <tr class="py-5">
                                        <th>#</th>
                                        <th>Course Name</th>
                                        <th>Full Name</th>
                                        <th>Preferred Time</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                        <!-- <th class="col-auto text-center">Action</th> -->
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM faculty_tbl f LEFT JOIN course_tbl c ON c.course_id = f.course_id";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        $row_count = 1;
                                        if ($rows) {
                                            foreach ($rows as $row):
                                    ?>
                                    <tr data-id="<?php echo $row['faculty_id']; ?>">
                                        <td width="50"><?php echo $row_count++?></td>
                                        <td data-course_id="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></td>
                                        <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                                        <td><?php echo date("h:i a", strtotime($row['pstart_time'])); ?> - <?php echo date("h:i a", strtotime($row['pend_time'])); ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td width="150">
                                            <div class="d-flex align-items-center column-gap-2">
                                                <div role="button" class="text-warning update_data">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </div>
                                                <div role="button" class="text-danger delete_data" data-id="<?php echo $row['faculty_id']; ?>" data-table="faculty_tbl" data-type="faculty_id" >
                                                    <i class="fa-solid fa-trash"></i>
                                                </div>
                                                <div role="button" class="text-primary view-preferred" data-faculty_id="<?php echo $row['faculty_id']; ?>">
                                                    <i class="fa-regular fa-eye"></i>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; } ?>


                                </tbody>
                            </table>
                         </div>
                    </div>
                    <div class="border bg-white d-flex flex-column row-gap-2 p-3">
                        <div >
                            <strong class="mb-2">List of F2F Schedule</strong>
                            <div class="row my-2 justify-content-between">
                                <form method="GET" action="f2f_print.php" class="col-6 d-flex align-items-center column-gap-2" target="_blank">
                                    <select required class="form-select form-select-sm rounded-1" name="course_id" id="course_id_filter">
                                        <option hidden selected value="">Choose Option Below</option>
                                        <?php
                                        // PHP code to fetch courses from database
                                        $sql = "SELECT * FROM course_tbl";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rows) {
                                            foreach ($rows as $row):
                                                ?>
                                                <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                    <select required class="form-select form-select-sm rounded-1" name="semester" id="semester">
                                        <option hidden selected value="">Choose Option Below</option>
                                        <option value="1">1st semester</option>
                                        <option value="2">2nd semester</option>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa-regular fa-folder"></i> Generate</button>
                                </form>

                                <div class="col-auto">
                                    <input type="text" id="search-bar" class="form-control form-control-sm rounded-1" placeholder="Search here">
                                </div>
                            </div>
                        </div>
                        
                        <div style="max-height: 70vh; overflow-y: auto;">
                            <table id="schedule_table" class="table table-bordered table-striped">
                                <thead class="position-sticky top-0">
                                    <tr class="py-5">
                                        <th>#</th>
                                        <th>Time & Date</th>
                                        <th>Subject</th>
                                        <th>Yr. & Sec.</th>
                                        <th>Room</th>
                                        <th>Course</th>
                                        <th>Teacher</th>
                                        <th>Sem</th>
                                        <th>Action</th>
                                        <!-- <th class="col-auto text-center">Action</th> -->
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $sql = "SELECT *, sch.semester AS sem FROM ol_schedule_tbl sch 
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
                                            <select class="form-select form-select-sm rounded-1 faculty_id_assigned2" name="faculty_id" data-schedule_id="<?php echo $row['ol_schedule_id']; ?>" data-subject_id="<?php echo $row['subject_id']; ?>" data-previous="<?php echo $row['faculty_id']; ?>">

                                                <?php
                                                if ($row['faculty_id'] === null) {
                                                    echo"<option hidden selected>No Teacher Assigned</option>";
                                                }else{
                                                    echo '<option selected value="'.$row['faculty_id'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
                                                }
                                                
                                                        $subject_id = $row['subject_id'];
                                                        $sql = "SELECT * FROM faculty_tbl f INNER JOIN preferred_subject_tbl ps ON ps.faculty_id = f.faculty_id WHERE ps.subject_id = :subject_id";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->bindParam(":subject_id", $subject_id);
                                                        $stmt->execute();
                                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                        if ($rows) {
                                                            foreach ($rows as $rowss):
                                                                if ($row['faculty_id'] != $rowss['faculty_id']) {
                                                                ?>
                                                                <option value="<?php echo $rowss['faculty_id']; ?>">
                                                                    <?php echo $rowss['first_name']." ".$rowss['last_name']; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            endforeach;
                                                        }else{
                                                            echo '<option value="" disabled>No Available Teacher</option>';
                                                        }
                                               
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php
                                                switch($row['sem']) {
                                                    case '1': echo "1st"; break;
                                                    case '2': echo "2nd"; break;
                                                    case null: echo "none"; break;
                                                }
                                            ?>
                                            semester
                                        </td>
                                        <td width="50">
                                            <div class="d-flex align-items-center column-gap-2">
                                                <div role="button" class="text-danger delete_data" data-id="<?php echo $row['ol_schedule_id']; ?>" data-table="ol_schedule_tbl" data-type="ol_schedule_id" >
                                                    <i class="fa-solid fa-trash"></i>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; } ?>


                                </tbody>
                            </table>
                        </div>
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
                                    <div class="text-muted ms-1" type="button" data-bs-dismiss="modal" id="change">change?</div>
                                   
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
        $('#change').click(async function() {
            var ol_schedule_id = $(this).data('id');

            const inputOptions = new Promise((resolve) => {
                setTimeout(() => {
                    resolve({
                        "approved": "Approved",
                        "cancelled": "Cancelled"
                    });
                }, 300);
            });

            const { value: status } = await Swal.fire({
                title: "Select remarks",
                input: "radio",
                inputOptions: await inputOptions,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return "You need to choose something!";
                    }
                }
            });

            if (status) {
                // Make the AJAX call here
                $.ajax({
                    url: '../controller/update_remarks.php', // Replace with your server endpoint
                    type: 'POST',
                    data: {
                        ol_schedule_id: ol_schedule_id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: 'Success',
                                text: `You selected: ${status}. The status has been updated.`,
                                icon: 'success'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: `There was an error updating the status: ${error}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });


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
                              
                                    _details.find('#change').attr('data-id', id);
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