<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="img/logo.png" alt="logo" style="width: 100px;">
        </div>
        <div class="sidebar-brand-text mx-3"> TIMECLASS </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dean_db.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#subject" aria-expanded="true"
            aria-controls="subject">
            <i class="fas fa-fw fa-book"></i>
            <span>Course & Subject</span>
        </a>
        <div id="subject" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Course & Subject</h6>
                <a class="collapse-item" href="add_subject.php">Add Subject</a>
                <a class="collapse-item" href="add_courses.php">Add Course</a>
                <a class="collapse-item" href="subject.php">Lists of all Subjects</a>
                <a class="collapse-item" href="courses.php">Lists of all Course</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#school_year" aria-expanded="true"
            aria-controls="school_year">
            <i class="fas fa-fw fa-book"></i>
            <span>School Year</span>
        </a>
        <div id="school_year" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">School Year</h6>
                <a class="collapse-item" href="school_year.php">Add School_Year</a>
                <a class="collapse-item" href="school_year_list.php">School_Year List</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-list"></i>
        <span>Teachers's Info</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Teacher's Information:</h6>
            <a class="collapse-item" href="teachers_lists.php">Teacher's Info</a>
            <a class="collapse-item" href="teachers_assigned_subjects.php" style="font-size: 12.5px;" >Teacher's Assigned Subjects</a>
        </div>
    </div>
</li>

    <!-- Heading -->

    <!-- Nav Item - Pages Collapse Menu -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
</ul>
<!-- End of Sidebar -->