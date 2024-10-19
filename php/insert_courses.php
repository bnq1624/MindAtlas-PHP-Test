<?php
include "dbconfig.php";

$conn = getConn();

$courses_data_file = "courses.csv";
# process and insert data to 'Courses' table
try {
    # try to open the file
    if (($file = fopen($courses_data_file, "r")) === FALSE) {
        throw new Exception("Failed to open the file $courses_data_file");
    }

    # read the csv file
    fgetcsv($file);

    $stmt = $conn->prepare("INSERT INTO Courses (description) VALUES (?)");
    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    $stmt->bind_param("s", $description);

    while (($data = fgetcsv($file)) !== FALSE) {
        $description = $data[0];

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute the statement: " . $stmt->error);
        }
    }

    $stmt->close();
    echo "Courses inserted successfully.";
    fclose($file);
}catch (Exception $e) {
    echo "Error " . $e->getMessage();
}

$conn->close();
?>