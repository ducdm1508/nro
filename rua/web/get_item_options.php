<?php
require_once "config.php";

header('Content-Type: application/json');

if (isset($_GET['item_id'])) {
    $item_id = intval($_GET['item_id']);
    
    $sql = "SELECT iso.id, iso.option_id, iso.param, iot.NAME as option_name
            FROM item_shop_option iso
            LEFT JOIN item_option_template iot ON iso.option_id = iot.id
            WHERE iso.item_shop_id = ?
            ORDER BY iso.id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
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