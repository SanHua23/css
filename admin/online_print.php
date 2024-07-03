<?php
include "../connect.php";

// Retrieve parameters from $_GET
$semester = $_GET['semester'] ?? '';
$course_id = $_GET['course_id'] ?? '';

// Prepare SQL query
$sql1 = "SELECT * FROM schedule_tbl sch 
        INNER JOIN course_tbl c ON c.course_id = sch.course_id
        WHERE sch.course_id = :course_id
        GROUP BY sch.course_id";

// Prepare and execute SQL1 statement
$stmt1 = $conn->prepare($sql1);
$stmt1->bindParam(':course_id', $course_id);
$stmt1->execute();
$rows1 = $stmt1->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Schedule Table</title>
    <style>
        .ff-roman {
            font-family: "Times New Roman", Times, serif;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
    <?php include "plugins-header.php"; ?>
</head>
<body>
    <div class="py-2 px">
        <div class="p-2 d-flex justify-content-between">
            <div>
                Online Schedule
            </div>
            <div>
                <div>
                    <span>Course: </span><b><?php echo $rows1['course_name']; ?></b>
                </div>
                <div>
                    <span>Semester: </span><b><?php echo $rows1['semester']; ?></b>
                </div>
            </div>
        </div>
        <table id="schedule_table" class="table p-2 table-borderless">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Day</th>
                    <th>Subject</th>
                    <th>Year & Section</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Prepare SQL query
                $sql = "SELECT * FROM schedule_tbl sch 
                        INNER JOIN course_tbl c ON c.course_id = sch.course_id
                        INNER JOIN section_tbl sec ON sec.section_id = sch.section_id
                        INNER JOIN faculty_tbl f ON f.faculty_id = sch.faculty_id
                        INNER JOIN time_tbl t ON t.time_id = sch.time_id
                        INNER JOIN subject_tbl sub ON sub.subject_id = sch.subject_id
                        WHERE sch.course_id = :course_id AND sch.semester = :semester";
                
                // Prepare and execute SQL statement
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':course_id', $course_id);
                $stmt->bindParam(':semester', $semester);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output rows in table body
                if ($rows) {
                    foreach ($rows as $row):
                ?>
                <tr class="border border-2">
                    <td>
                        <?php echo date("h:i a", strtotime($row['start_time'])); ?> - 
                        <?php echo date("h:i a", strtotime($row['end_time'])); ?>
                    </td>
                    <td>
                        <?php
                            switch($row['days']) {
                                case 'M': echo "Monday"; break;
                                case 'T': echo "Tuesday"; break;
                                case 'W': echo "Wednesday"; break;
                                case 'TH': echo "Thursday"; break;
                                case 'F': echo "Friday"; break;
                                case 'S': echo "Saturday"; break;
                            }
                        ?>
                    </td>
                    <td><?php echo $row['subject_title']; ?></td>
                    <td><?php echo $row['year_level']."-".$row['section']; ?></td>
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
                <?php endforeach; }else{ ?>
                    <tr>
                        <td colspan="100"class="text-center">No data available</td>
                    </tr>
                <?php } ?>        
            </tbody>
        </table>
    </div>
    <?php include "plugins-footer.php"; ?>
    <script>
        // JavaScript for printing and closing window after print
        window.print();  
        window.onafterprint = window.close; 
    </script>
</body>
</html>
