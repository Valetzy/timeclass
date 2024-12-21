<?php
include '../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schoolYear = $_POST['school_year'];

    // Split school year into start and end year
    list($startYear, $endYear) = explode('-', $schoolYear);

    // Display the counts filtered by school year
    echo countEntries('courses', 'Courses', $startYear, $endYear);
    echo countEntries('subjects', 'Subjects', $startYear, $endYear);
    echo teachers('teacher', 'Teachers', $startYear, $endYear);
    echo countEntries('attendance', 'Attendance', $startYear, $endYear);
}

function countEntries($table, $label, $startYear, $endYear) {
    global $conn;

    // Build the SQL query to count the entries
    $sql = "SELECT COUNT(*) AS count FROM $table WHERE year >= ? AND year <= ?";
    
    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startYear, $endYear);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Return the count wrapped in HTML
    return '
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . htmlspecialchars($label) . '</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $row['count'] . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}


function teachers($tableName, $cardTitle, $startYear, $endYear)
{
    global $conn;
    $sql = "SELECT COUNT(*) as entry_count FROM $tableName WHERE confirmation = 1 AND school_year_start = '$startYear' AND school_year_end = '$endYear'";
    $result = $conn->query($sql);

    $entry_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $entry_count = $row['entry_count'];
    }

    return '<div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">'
                                    . $cardTitle . 
                                '</div>
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
