<?php
include "dbconfig.php";

$conn = getConn();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$statuses = ['not started', 'in progress', 'completed'];

try {
    # get users ids from Users table
    $sql_users = "SELECT user_id FROM Users";
    $users_ids_result = $conn->query($sql_users);

    if ($users_ids_result === FALSE) {
        throw new Exception("Failed to retrieve users ids: " . $conn->error);
    }

    # get courses ids from Courses table
    $sql_courses = "SELECT course_id FROM Courses";
    $courses_ids_result = $conn->query($sql_courses);
    if ($courses_ids_result === FALSE) {
        throw new Exception("Failed to retrieve courses ids: " . $conn->error);
    }

    $user_ids = [];
    while ($user_row = $users_ids_result->fetch_assoc()) {
        $user_ids[] = $user_row["user_id"];
    }

    $course_ids = [];
    while ($course_row = $courses_ids_result->fetch_assoc()) {
        $course_ids[] = $course_row["course_id"];
    }

    # process and insert data to the Enrolments table
    $stmt = $conn->prepare("INSERT INTO Enrolments (user_id, course_id, completion_status) VALUES (?, ?, ?)");
    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    // bind the parameter to the statement
    $stmt->bind_param("iis", $user_id, $course_id, $completion_status);

    foreach ($user_ids as $user_id) {
        foreach ($course_ids as $course_id) {
            $completion_status = $statuses[array_rand($statuses)];

            // execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute the enrolment insertion: " . $stmt->error);
            }
        }
    }

    $stmt->close();

    echo "Enrolments inserted successfully.";
}catch (Exception $e) {
    echo "Error inserting enrolments: " . $e->getMessage();
}finally {
    $conn->close();
}
?>