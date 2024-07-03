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
	        		<form id="add_time" class="border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set Time and Day Form</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					    <div class="form-group">
					    	<label class="form-label">Day/s:</label>
					    	<div class="row d-flex align-items-center">
							    <div class="form-group col-4">
							        <input type="checkbox" name="days[]" value="M" id="monday" class="form-check-input border-2 border-warning">
							        <label class="form-label" for="monday">Monday</label>
							    </div>
							    <div class="form-group col-4">
							    	<input type="checkbox" name="days[]" value="T" id="tuesday" class="form-check-input border-2 border-warning">
							        <label class="form-label" for="tuesday">Tuesday</label>
							        
							    </div>
							    <div class="form-group col-4">
							         <input type="checkbox" name="days[]" value="W" id="wednesday" class="form-check-input border-2 border-warning">
							         <label class="form-label" for="wednesday">Wednesday</label>
							    </div>
							    <div class="form-group col-4">
							        <input type="checkbox" name="days[]" value="TH" id="thursday" class="form-check-input border-2 border-warning">
							        <label class="form-label" for="thursday">Thursday</label>
							    </div>
							    <div class="form-group col-4">
							        <input type="checkbox" name="days[]" value="F" id="friday" class="form-check-input border-2 border-warning">
							        <label class="form-label" for="friday">Friday</label>
							    </div>
							    <div class="form-group col-4">
							        <input type="checkbox" name="days[]" value="S" id="saturday" class="form-check-input border-2 border-warning">
							        <label class="form-label" for="saturday">Saturday</label>
							    </div>
						    </div>
					    </div>
					    <div class="d-flex align-items-center column-gap-1">
							<div class="form-floating w-100">
							  <input required type="time" name="start_time" id="start_time" class="form-control form-control-sm rounded-1">
							  <label for="pstart_time">Start Time</label>
							</div>
							<div class="form-floating w-100">
							  <input required type="time" name="end_time" id="end_time" class="form-control form-control-sm rounded-1">
							  <label for="pend_time">End Time</label>
							</div>
						</div>

						<div class="form-floating">
				    		<input type="text" name="total_time" id="timevalue" class="form-control rounded-1" placeholder="total" readonly>
				    		<label for="timevalue">Total Time</label>
						</div>
					    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-8">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div class="d-flex align-items-center justify-content-between">
	        				<strong class="mb-2">List of Time and Day</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="time_table" class="table table-bordered table-striped">
							    <thead class="position-sticky top-0">
							        <tr class="py-5">
							            <th>#</th>
							            <th>Start Time</th>
							            <th>End Time</th>
							            <th>Day</th>
							            <th>Action</th>
							        </tr>
							    </thead>
							    <tbody>
							        <?php
							        // PHP code to fetch and display existing data
							        $sql = "SELECT * FROM time_tbl";
							        $stmt = $conn->prepare($sql);
							        $stmt->execute();
							        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

							        $row_count = 1;
							        if ($rows) {
							            foreach ($rows as $row):
							        ?>
							        <tr data-id="<?php echo $row['time_id']; ?>">
							            <td width="50"><?php echo $row_count++?></td>
							            <td><?php echo date("h:i A", strtotime($row['start_time'])); ?></td>
							            <td><?php echo date("h:i A", strtotime($row['end_time'])); ?></td>
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
							            <td width="150">
							                <div class="d-flex align-items-center column-gap-2">
							                    <div role="button" class="text-danger delete_data" data-id="<?php echo $row['time_id']; ?>" data-table="time_tbl" data-type="time_id">
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
   	<script>
   		 // Select the input elements using jQuery
        const $startTimeInput = $('#start_time');
        const $endTimeInput = $('#end_time');
        const $timeValueInput = $('#timevalue');

        // Function to calculate time difference
        function calculateTime() {
            const startTime = $startTimeInput.val();
            const endTime = $endTimeInput.val();

            if (startTime && endTime) {
                // Parse times into Date objects (date is irrelevant)
                const startDate = new Date(`2000-01-01T${startTime}`);
                const endDate = new Date(`2000-01-01T${endTime}`);

                // Calculate time difference in milliseconds
                let timeDiff = endDate - startDate;

                // Convert milliseconds to hours and minutes
                let hours = Math.floor(timeDiff / (1000 * 60 * 60));
                let minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));

                // Format output as HH:mm
                let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

                // Update the input field with the computed time
                $timeValueInput.val(formattedTime);
            } else {
                $timeValueInput.val(''); // Clear the value if one of the inputs is empty
            }
        }

        // Event listeners using jQuery to calculate on input change
        $startTimeInput.on('input', calculateTime);
        $endTimeInput.on('input', calculateTime);
   	</script>
</body>
</html>


