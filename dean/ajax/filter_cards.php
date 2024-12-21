<?php
session_start();

if (!isset($_POST['school_year'])) {
    echo 'Error: School year not provided';
    exit();
}

include '../conn/conn.php';

$schoolYear = $_POST['school_year'];

// Function to count entries based on the school year
function countEntries($tableName, $cardTitle, $schoolYear)
{
    include '../conn/conn.php';

    // SQL query to count the number of entries based on the selected school year
    $sql = "SELECT COUNT(*) as entry_count FROM $tableName WHERE school_year = '$schoolYear'";
    $result = $conn->query($sql);

    $entry_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $entry_count = $row['entry_count'];
    }

    return '
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ' . $cardTitle . '</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $entry_count . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function teachers($tableName, $cardTitle, $schoolYear)
{
    include '../conn/conn.php';

    $sql = "SELECT COUNT(*) as entry_count FROM $tableName WHERE confirmation = 1 AND school_year = '$schoolYear'";
    $result = $conn->query($sql);

    $entry_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $entry_count = $row['entry_count'];
    }

    return '
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ' . $cardTitle . '</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $entry_count . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

// Output updated card information
echo countEntries('student', 'Total Student Enrolled', $schoolYear);
echo countEntries('subjects', 'Total Subjects', $schoolYear);
echo countEntries('assign', 'Total Courses Assigned', $schoolYear);
echo teachers('teacher', 'Total Confirmed Teachers', $schoolYear);
?>
