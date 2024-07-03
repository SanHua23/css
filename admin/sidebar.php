<?php include "session_start.php"; ?>
<!-- topbar start -->
    <div class="topbar bg-white d-flex justify-content-between shadow-sm p-0">
        <div class="topbar-logo d-flex align-items-center py-3 px-3 ">
            <div class="left-logo fw-bolder text-nowrap overflow-hidden d-flex">
            	<div class="logo-text fs-5 fw-bold text-red">
                    <img src="../images/logo.png" width="30" height="auto">
                    <span>PUP SCHEDULING</span>
                </div>
            </div>
            <div role="button" id="menu-toggle">
                <i class="fa-solid fa-toggle-off" id="menu-toggle-icon"></i>
            </div>
        </div>
        <div class="d-flex align-items-center column-gap-3 py-3 px-4">
            <a href="notification" class="position-relative bg-secondary-subtle rounded-circle p-0 d-flex">
                <i class="fa-regular fa-bell m-auto rounded-circle p-2 border border-secondary-subtle border-2 text-secondary"></i>
                <span class="badge text-bg-danger position-absolute" style="right: -5px; top: -5px">
                    <?php
                        $sql = "
                                SELECT COUNT(*) AS total_count FROM notification_tbl 
                                WHERE status = 'new'
                                ORDER BY notification_id DESC
                                ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row['total_count'] > 0) {
                            echo $row['total_count'];
                        }else{
                            echo '0';
                        }
                        
                    ?>
                </span>
            </a>
            
            <div class="dropdown">
              <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-user bg-secondary-subtle  rounded-circle p-2 border border-secondary-subtle border-2 text-secondary"></i>
              </div>
              <ul class="dropdown-menu">
                <li class="px-4 mb-2"><p class="fw-bold m-0">Hello, <?php echo $_SESSION['first_name']; ?>!</p></li>
                <li><a class="dropdown-item d-flex align-items-center column-gap-2" href="logout.php"><i class="fa-solid fa-power-off text-secondary"></i> <span>Logout</span></a></li>
              </ul>
            </div>
        </div>
    </div>
    <!-- topbar end -->

    <!-- sidebar start -->
    <div class="sidebar">
        <ul class="navbar-nav p-3">

            <li class="nav-item">
                <a href="course" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-solid fa-shapes"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Course</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="section" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-regular fa-file"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Section</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="room" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-brands fa-buromobelexperte"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Room</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="subject" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-solid fa-book"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Subject</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="faculty" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-solid fa-users"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Faculty</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="time" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-solid fa-clock"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Time</small>
                </a>
            </li>
            <li class="nav-item">
                <a href="f2f-schedule" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-solid fa-calendar-days"></i>
                    </div>
                    <small class="fw-bold text-nowrap">F2F Schedule</small>
                </a>
            </li>

            <li class="nav-item">
                <a href="online-schedule" class="py-2 nav-link d-flex align-items-center justify-content-start column-gap-3">
                    <div class="icon-size">
                        <i class="sidebar-icon-size fa-regular fa-calendar"></i>
                    </div>
                    <small class="fw-bold text-nowrap">Online Schedule</small>
                </a>
            </li>
        </ul>
    </div>
    <!-- sidebar end -->