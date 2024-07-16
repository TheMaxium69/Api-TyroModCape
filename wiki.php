<?php

require "db.php";

header('Content-Type: application/json');

// Create a SQL query string
$sql = "SELECT * FROM capes";
$stmt = $db->prepare($sql);
$stmt->execute();

// Fetch all the records
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as &$res) {
    $idCape = $res['id'];
    $sql3 = "SELECT COUNT(*) FROM `players` WHERE `idCapes` = '$idCape'";
    $stmt3 = $db->prepare($sql3);
    $stmt3->execute();
    $resultNbCape = $stmt3->fetch();
    $res["nbBuy"] = $resultNbCape['0'];
}

// Output the results in JSON format
echo json_encode($result);

