<?php 
include "../connect.php"; 

$status = 'old';

$sqlUpdate = "UPDATE notification_tbl SET status = :status WHERE status = 'new'";
$updateQuery = $conn->prepare($sqlUpdate);
$updateQuery->bindParam(':status', $status);
$updateQuery->execute();
$existing = $updateQuery->fetch(PDO::FETCH_ASSOC);

  

?>

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
	        				<strong class="mb-2">Notification Logs for Faculty Update</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="notification_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Faculty Name</th>
			                            <th>Description</th>
			                            <th>Date & Time</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT * FROM notification_tbl n
	                                    		INNER JOIN user_tbl u ON u.user_id = n.user_id";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr>
								        <td width="50"><?php echo $row_count++?></td>
								        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
								        <td><?php echo $row['description']; ?></td>
								        <td>
								        	<?php echo date("F d, Y h:i a", strtotime($row['date_time'])); ?>
								        </td>
								        <td width="150">
								        	<div class="d-flex align-items-center column-gap-2">
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['notification_id']; ?>" data-table="notification_tbl" data-type="notification_id" >
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


