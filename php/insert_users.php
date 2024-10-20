<?php
include "dbconfig.php";

$conn = getConn();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$users_data_file = "users.csv";
# process and insert data to 'Users' table 
try {
    if (!is_readable($users_data_file)) {
        throw new Exception("The file $users_data_file is not readable.");
    }

    if (($file = fopen($users_data_file, "r")) === FALSE) {
        throw new Exception("Failed to open the file $users_data_file");
    }

    # read the csv file
    fgetcsv($file);

    $stmt = $conn->prepare("INSERT INTO Users (firstname, surname) VALUES (?, ?)");
    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    // bind the parameter to the statement
    $stmt->bind_param("ss", $firstname, $surname);

    // process through each row of the csv file
    while (($data = fgetcsv($file)) !== FALSE) {
        // validate the row data
        if (isset($data[0], $data[1]) && !empty(trim($data[0])) && !empty(trim($data[1]))) {
            $firstname = $data[0];
            $surname = $data[1];

            // execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute the statement: " . $stmt->error);
            }
        }
    }

    $stmt->close();
    
    echo "Users inserted successfully.";
}catch (Exception $e) {
    echo "Error inserting users: " . $e->getMessage();
}finally {
    if (isset($file) && is_resource($file)) {
        fclose($file);
    }

    if ($conn) {
        $conn->close();
    }
}
?>