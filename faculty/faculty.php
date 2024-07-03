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
	        	<div class="col-12 col-lg-6 d-flex flex-column row-gap-3">
	        		<!-- inserting teacher infor -->
	        		<form class="add_faculty border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Update your Details</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					     <?php
				            $sql = "SELECT * FROM faculty_tbl WHERE user_id = :user_id";
				            $stmt = $conn->prepare($sql);
				            $stmt->bindParam(":user_id", $_SESSION['user_id']);
				            $stmt->execute();
				            $row = $stmt->fetch(PDO::FETCH_ASSOC);
				        ?>

					    <input type="text" name="faculty_id" id="faculty_id" class="form-control rounded-1" value="<?php echo $row['faculty_id']?>" hidden>
					    <input type="text" name="user_id" id="user_id" class="form-control rounded-1" value="<?php echo $_SESSION['user_id']?>" hidden>
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
						  <input required type="text" name="first_name" id="first_name" class="form-control rounded-1" value="<?php echo $_SESSION['first_name']; ?>" readonly>
						  <label for="first_name">First Name</label>
						</div>
						<div class="form-floating">
						  <input required type="text" name="last_name" id="last_name" class="form-control rounded-1"  value="<?php echo $_SESSION['last_name']; ?>" readonly>
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
					    <button type="submit" class="save-button btn btn-primary fw-bold">Update</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-6 d-flex flex-column row-gap-3">

	        		<!-- inserting teacher preferred subject -->
	        		<form class="add_faculty border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Update your Preferred Subject</strong>
					    </div>
					    <input type="text" name="request" class="form-control rounded-1" value="update" hidden>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="faculty_id" id="faculty_id_select">
					            <option hidden selected value="">Choose option below</option>
					            <?php
					            $sql = "SELECT * FROM faculty_tbl f 
	                                    		INNER JOIN user_tbl u ON u.user_id = f.user_id
	                                    		WHERE u.user_id = :user_id";
					            $stmt = $conn->prepare($sql);
					            $stmt->bindParam(':user_id', $_SESSION['user_id']);
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
	        </div>


	    </div>
	    <!-- content container end-->
    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


