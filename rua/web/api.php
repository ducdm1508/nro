<?php
header('Content-Type: application/json; charset=utf-8');
require_once "config.php";

// --- Thêm mới option ---
if (isset($_POST['add_option'])) {
    $item_id = $_POST['item_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("INSERT INTO item_shop_option (item_shop_id, option_id, param) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $item_id, $option_id, $param);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm option thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi thêm option']);
    }
    $stmt->close();
    exit;
}

// --- Xóa option ---
if (isset($_GET['delete_option'])) {
    $option_id = $_GET['delete_option'];
    if ($conn->query("DELETE FROM item_shop_option WHERE id = $option_id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// --- Xóa item_shop ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM item_shop WHERE id = $id");
    $conn->query("DELETE FROM item_shop_option WHERE item_shop_id = $id");
    echo json_encode(['success' => true]);
    exit;
}

// --- Sửa item_shop ---
if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $tab_id = $_POST['tab_id'];
    $is_new = $_POST['is_new'];
    $is_sell = $_POST['is_sell'];
    $type_sell = $_POST['type_sell'];
    $cost = $_POST['cost'];

    $stmt = $conn->prepare("UPDATE item_shop SET tab_id=?, is_new=?, is_sell=?, type_sell=?, cost=? WHERE id=?");
    $stmt->bind_param("iiiiii", $tab_id, $is_new, $is_sell, $type_sell, $cost, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    exit;
}

// --- Thêm mới item_shop ---
if (isset($_POST['add'])) {
    $tab_id = $_POST['tab_id'];
    $temp_id = $_POST['temp_id'];
    $is_new = $_POST['is_new'];
    $is_sell = $_POST['is_sell'];
    $type_sell = $_POST['type_sell'];
    $cost = $_POST['cost'];

    $icon_query = $conn->prepare("SELECT icon_id FROM item_template WHERE id = ?");
    $icon_query->bind_param("i", $temp_id);
    $icon_query->execute();
    $icon_query->bind_result($icon_id);
    $icon_query->fetch();
    $icon_query->close();

    $stmt = $conn->prepare("INSERT INTO item_shop (tab_id, temp_id, is_new, is_sell, type_sell, cost, icon_spec) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiii", $tab_id, $temp_id, $is_new, $is_sell, $type_sell, $cost, $icon_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>