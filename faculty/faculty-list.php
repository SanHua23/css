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
	        	<div class="col-12 d-flex flex-column row-gap-3">
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
	                                    $sql = "SELECT * FROM faculty_tbl f 
	                                    		LEFT JOIN course_tbl c ON c.course_id = f.course_id
	                                    		INNER JOIN user_tbl u ON u.user_id = f.user_id
	                                    		WHERE u.user_id = :user_id";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
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


