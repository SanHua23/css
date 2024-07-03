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
	        	<div class="col-12 col-lg-4 d-flex flex-column row-gap-3">
	        		<!-- inserting teacher infor -->
	        		<form class="add_faculty border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set Faculty Form</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					    <input type="text" name="faculty_id" id="faculty_id" class="form-control rounded-1" value="0" hidden>
					    <input type="text" name="request" class="form-control rounded-1" value="inserting" hidden>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="course_id" id="course_id_select2">
					            <option hidden selected value="">Choose option below</option>
					            <?php
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
					        <label for="course_id">Choose Course</label>
					    </div>
					    <div class="form-floating">
						  <input required type="text" name="first_name" id="first_name" class="form-control rounded-1" placeholder="input first name">
						  <label for="first_name">First Name</label>
						</div>
						<div class="form-floating">
						  <input required type="text" name="last_name" id="last_name" class="form-control rounded-1" placeholder="input last name">
						  <label for="last_name">Last Name</label>
						</div>
						<div class="d-flex align-items-center column-gap-1">
							<div class="form-floating w-100">
							  <input required type="time" name="pstart_time" id="pstart_time" class="form-control form-control-sm rounded-1">
							  <label for="pstart_time">Start Time</label>
							</div>
							<div class="form-floating w-100">
							  <input required type="time" name="pend_time" id="pend_time" class="form-control form-control-sm rounded-1">
							  <label for="pend_time">End Time</label>
							</div>
						</div>

					    <div class="form-floating">
						  <textarea required type="text" name="description" id="description" class="form-control form-control-sm rounded-1" placeholder="Write the background of professor here"></textarea>
						  <label for="description">Description</label>
						</div>
					    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
					</form>

					<!-- inserting teacher preferred subject -->
	        		<form class="add_faculty border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Update Preferred Subject</strong>
					    </div>
					    <input type="text" name="request" class="form-control rounded-1" value="update" hidden>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="faculty_id" id="faculty_id_select">
					            <option hidden selected value="">Choose option below</option>
					            <?php
					            $sql = "SELECT * FROM faculty_tbl";
					            $stmt = $conn->prepare($sql);
					            $stmt->execute();
					            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

					            if ($rows) {
					                foreach ($rows as $row):
					                    ?>
					                    <option data-index="<?php echo $row['faculty_id']; ?>" value="<?php echo $row['faculty_id']; ?>"><?php echo $row['first_name']." ".$row['last_name']; ?></option>
					                <?php
					                endforeach;
					            }
					            ?>
					        </select>
					        <label for="faculty_id">Faculty Teacher</label>
					    </div>

					    <div class="form-floating ">
					        <div class="dropdown">
							  <a class="text-decoration-none border px-3 py-2 rounded-1 w-100  bg-transparent d-flex flex-column" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							  	<small for="pend_time" class="text-secondary">Preferred Subject</small>
							  	<div class="d-flex align-items-center text-dark">
							  		<div>
							    	Choose option below
								    </div>
								    <i class="fa-solid fa-angle-down ms-auto text-secondary"></i>
							  	</div>
							    
							  </a>

							  <div class="dropdown-menu w-100">
							  	<div id="preferred-subject" class="row mx-0 d-flex align-items-center">
							  		<div class="col-12">Please choose faculty teacher!</div>
							        <!-- Dynamically loaded subjects will appear here -->
							    </div>
							  </div>
							</div>
					    </div>
					    <button type="submit" class="save-button btn btn-primary fw-bold">Update</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-8 d-flex flex-column row-gap-3">
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
	        	</div>
	        </div>


	    </div>
	    <!-- content container end-->
    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


