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
	        	<div class="col-12 col-lg-4">
	        		<!-- inserting -->

	        		<form id="add_schedule" class="border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set Online Schedule</strong>
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
						    <select required class="form-select rounded-1" name="subject_id" id="subject_id_select" disabled>
						        <option hidden selected value="">Choose Option Below</option>
						    </select>
						    <label for="subject_id">Subject:</label>
						</div>

						<div class="form-floating">
						    <select required class="form-select rounded-1" name="section_id" id="section_id_select" disabled>
						        <option hidden selected value="">Choose Option Below</option>
						    </select>
						    <label for="section_id">Year and Section:</label>
						</div>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="time_id" id="time_id">
					            <option hidden selected value="">Choose Option Below</option>
					            <?php
					            $sql = "SELECT * FROM time_tbl";
					            $stmt = $conn->prepare($sql);
					            $stmt->execute();
					            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

					            if ($rows) {
					                foreach ($rows as $row):
					                    ?>
					                    <option value="<?php echo $row['time_id']; ?>">
					                    	<?php echo date("h:i A", strtotime($row['start_time'])); ?> - <?php echo date("h:i A", strtotime($row['end_time'])); ?> 
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
								        </option>
					                <?php
					                endforeach;
					            }
					            ?>
					        </select>
					        <label for="time_id">Time and Day:</label>
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
                                <form method="GET" action="online_print.php" class="col-6 d-flex align-items-center column-gap-2" target="_blank">
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

                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap"><i class="fa-regular fa-folder"></i> Generate</button>
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
			                            <th>Time</th>
			                            <th>Day</th>
			                            <th>Subject</th>
			                            <th>Year & Section</th>
			                            <th>Course</th>
			                            <th>Teacher</th>
			                            <th>Sem</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT *, sch.semester sem FROM schedule_tbl sch 
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
								        	<select class="form-select form-select-sm rounded-1 faculty_id_assigned" name="faculty_id" data-schedule_id="<?php echo $row['schedule_id']; ?>" data-subject_id="<?php echo $row['subject_id']; ?>" data-previous="<?php echo $row['faculty_id']; ?>">

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
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['schedule_id']; ?>" data-table="schedule_tbl" data-type="schedule_id" >
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
    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


