<?php
include "dbconfig.php";

$conn = getConn();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$courses_data_file = "courses.csv";
# process and insert data to 'Courses' table
try {
    if (!is_readable($courses_data_file)) {
        throw new Exception("The file $courses_data_file does not exist or is not readable.");
    }

    if (($file = fopen($courses_data_file, "r")) === FALSE) {
        throw new Exception("Failed to open the file $courses_data_file");
    }

    # read the csv file
    fgetcsv($file);

    $stmt = $conn->prepare("INSERT INTO Courses (description) VALUES (?)");
    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    // bind the parameter to the statement
    $stmt->bind_param("s", $description);

    // process through each row of the csv file
    while (($data = fgetcsv($file)) !== FALSE) {
        // validate the row data
        if (isset($data[0]) && !empty($data[0])) {
            $description = trim($data[0]);

            // execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute the statement: " . $stmt->error);
            }
        }
    }

    $stmt->close();

    echo "Courses inserted successfully.";
}catch (Exception $e) {
    echo "Error inserting courses: " . $e->getMessage();
}finally {
    if (isset($file) && is_resource($file)) {
        fclose($file);
    }

    if ($conn) {
        $conn->close();
    }
}
?>