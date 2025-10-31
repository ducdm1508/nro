<?php
header('Content-Type: application/json; charset=utf-8');

require_once "config.php";

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $search = '%' . $query . '%';
    
    $sql = "SELECT id, NAME 
            FROM item_option_template
            WHERE NAME LIKE ?
            ORDER BY NAME ASC
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    
    $stmt->close();
    echo json_encode($options);
} else {
    echo json_encode([]);
}
?>