<?php

require "db.php";

header('Content-Type: application/json');

// Create a SQL query string
$sql = "SELECT * FROM capes";
$stmt = $db->prepare($sql);
$stmt->execute();

// Fetch all the records
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output the results in JSON format
echo json_encode($result);

