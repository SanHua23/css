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
	        		<form id="add_section" class="border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set Section Form</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					    <input type="text" name="section_id" id="section_id" class="form-control rounded-1" value="0" hidden>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="course_id" id="course_id">
					            <option hidden selected value="">Choose Option Below</option>
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
					        <label for="course_id">Course Name:</label>
					    </div>
					    <div class="form-floating">
					        <input required type="text" name="section" id="section" class="form-control rounded-1" placeholder="Type section here">
					        <label for="section">Section:</label>
					    </div>
					    <div class="form-floating">
					        <select required class="form-select rounded-1" name="year_level" id="year_level">
					            <option hidden selected value="">Choose Option Below</option>
					            <option value="1">1st Year</option>
					            <option value="2">2nd Year</option>
					            <option value="3">3rd Year</option>
					            <option value="4">4th Year</option>
					        </select>
					        <label for="year_level">Year Level:</label>
					    </div>
					    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-8">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div class="d-flex align-items-center justify-content-between">
	        				<strong class="mb-2">List of Section</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="section_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Course Name</th>
			                            <th>Year</th>
			                            <th>Section</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT * FROM section_tbl s INNER JOIN course_tbl c ON c.course_id = s.course_id";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr data-id="<?php echo $row['section_id']; ?>">
								        <td width="50"><?php echo $row_count++?></td>
								        <td data-course_id="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></td>
								        <td><?php echo $row['year_level']; ?></td>
								        <td><?php echo $row['section']; ?></td>
								        <td width="150">
								        	<div class="d-flex align-items-center column-gap-2">
								        		<div role="button" class="text-warning update_data">
								        			<i class="fa-solid fa-pen-to-square"></i>
								        		</div>
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['section_id']; ?>" data-table="section_tbl" data-type="section_id" >
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


