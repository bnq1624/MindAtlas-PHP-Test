<?php
include "dbconfig.php";

$conn = getConn();

$users_data_file = "users.csv";
# process and insert data to 'Users' table 
try {
    # try to open the file
    if (($file = fopen($users_data_file, "r")) === FALSE) {
        throw new Exception("Failed to open the file $users_data_file");
    }

    # read the csv file
    fgetcsv($file);

    $stmt = $conn->prepare("INSERT INTO Users (firstname, surname) VALUES (?, ?)");
    if ($stmt === FALSE) {
        throw new Exception("Failed to prepare the sql statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $firstname, $surname);

    while (($data = fgetcsv($file)) !== FALSE) {
        $firstname = $data[0];
        $surname = $data[1];

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute the statement: " . $stmt->error);
        }
    }

    $stmt->close();
    echo "Users inserted successfully.";
    fclose($file);
}catch (Exception $e) {
    echo "Error " . $e->getMessage();
}

$conn->close();
?>