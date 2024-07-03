<?php
// session_start();
// include 'connect.php';



// if (isset($_SESSION['role'])) {
// 	if ($_SESSION['role'] === 'admin') {
// 		header('location: admin/dashboard');
// 	}else if ($_SESSION['role'] === 'teller') {
// 		header('location: teller/records');
// 	}
// }

?>

<?php include 'plugins-header.php';?>
   
<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-10 col-sm-8 col-md-6 col-xl-6 col-xxl-5 row mx-0 shadow-lg bg-white rounded flex-column-reverse flex-lg-row px-4">
        <div class="col-12 col-xl-6 d-none d-xl-flex px-0">
            <img src="images/logo.png" class="rounded-end m-auto" height="250" width="250">
        </div>
        <form id="register_account" class="col-12 col-xl-6 p-5 px-lg-4 d-flex flex-column justify-content-center bg-white">
            <div class="mb-4 d-flex flex-column align-items-center">
                <h4 class="text-center fw-bold">Register</h4>
                <div class="text-secondary">Create new account</div>
            </div>
            <div class="input-group mb-3">
              <input type="text" class="form-control  py-2" name="first_name" placeholder="your first name" required>
              <input type="text" class="form-control  py-2" name="last_name" placeholder="your last name" required>
            
            </div>

            <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" placeholder="sample@gmail.com" required>
                <div class="input-group-append">
                    <div class="py-2 px-3 fs-5 border">
                        <span class="fas fa-solid fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-4">
                <input type="password" class="form-control" name="password"  id="password" placeholder="create password" required>
                <div class="input-group-append">
                    <div class="py-2 px-3 fs-5 border">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-4">
               <select class="form-select py-2" name="user_role" required>
                    <option selected hidden value="">Select Role</option>
                    <option value="2">Faculty</option>
                    <option value="3">Student</option>
               </select>
            </div>
            <input type="submit" class="btn btn-warning fw-bold shadow" value="REGISTER">
            <div class="mt-4 text-center">
                <small>Already had account? <a href="login">Login Here</a></small>
            </div>
      </form>

   </div>
</div>

<?php include 'loader.php' ?>
<?php include 'plugins-footer.php' ?>
