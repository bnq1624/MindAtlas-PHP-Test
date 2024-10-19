<?php
include "dbconfig.php";

$conn = getConn();

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

    # process and insert data to the Enrolments table
    $stmt = $conn->prepare("INSERT INTO Enrolments (user_id, course_id, completion_status) VALUES (?, ?, ?)");

    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    $stmt->bind_param("iis", $user_id, $course_id, $completion_status);

    # loop through each user
    while ($user_row = $users_ids_result->fetch_assoc()) {
        $user_id = $user_row["user_id"];

        while ($course_row = $courses_ids_result->fetch_assoc()) {
            $course_id = $course_row["course_id"];

            $random_status = array_rand($statuses);
            $completion_status = $statuses[$random_status];

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute the enrolment insertion: " . $stmt->error);
            }
        }

        # reset the course results to process the next user
        $courses_ids_result->data_seek(0);
    }

    $stmt->close();
    echo "Enrolments inserted successfully.";
}catch (Exception $e) {
    echo "Error " . $e->getMessage();
}

$conn->close();
?>