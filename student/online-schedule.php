<?php include "../connect.php"; ?>

<!DOCTYPE html>
<html>
<!-- header link -->
<?php include "plugins-header.php"; ?>
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
	        				<strong class="mb-2">List of Online Schedule</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="schedule_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Time</th>
			                            <th>Day</th>
			                            <th>Subject</th>
			                            <th>Year & Section</th>
			                            <th>Course</th>
			                            <th>Teacher</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT * FROM schedule_tbl sch 
	                                    		INNER JOIN course_tbl c ON c.course_id = sch.course_id
	                                    		INNER JOIN section_tbl sec ON sec.section_id = sch.section_id
	                                    		INNER JOIN time_tbl t ON t.time_id = sch.time_id
	                                    		LEFT JOIN faculty_tbl f ON f.faculty_id = sch.faculty_id
	                                    		INNER JOIN subject_tbl sub ON sub.subject_id = sch.subject_id";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr data-id="<?php echo $row['schedule_id']; ?>">
								        <td width="50"><?php echo $row_count++?></td>
								        <td>
								        	<?php echo date("h:i a", strtotime($row['start_time'])); ?> - <?php echo date("h:i a", strtotime($row['end_time'])); ?>
								        	
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
								    <?php   endforeach; 
                                        }else{
                                            echo "<tr><td colspan='100' class='text-center'>No Online Schedule</td></tr>";
                                        } 
                                    ?>
							 	</tbody>
						 	</table>
						 </div>
					</div>
	        	</div>
	        </div>


	    </div>
	    <!-- content container end-->
    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


