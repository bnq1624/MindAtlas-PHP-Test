<?php
include "dbconfig.php";

try {
    $conn = getConn();

    # get the data to display in the web
    $sql = "
    SELECT u.firstname, u.surname, c.description, e.completion_status
    FROM Enrolments e
    JOIN Users u
    ON e.user_id = u.user_id
    JOIN Courses c
    ON e.course_id = c.course_id";

    $result = $conn->query($sql);

    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;     # append the fetched row to the data array
        }
    }

    # return the data in JSON format
    header("Content-Type: application/json");
    echo json_encode($data);
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>