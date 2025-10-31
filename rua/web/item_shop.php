<?php
require_once "config.php";

// --- Thêm mới item_shop ---
if (isset($_POST['add'])) {
    $id = intval($_POST['id']);
    $tab_id = intval($_POST['tab_id']);
    $temp_id = intval($_POST['temp_id']);
    $is_new = intval($_POST['is_new']);
    $is_sell = intval($_POST['is_sell']);
    $type_sell = intval($_POST['type_sell']);
    $cost = intval($_POST['cost']);
    $icon_spec = intval($_POST['icon_spec']);

    $stmt = $conn->prepare("INSERT INTO item_shop (id, tab_id, temp_id, is_new, is_sell, type_sell, cost, icon_spec) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiiii", $id, $tab_id, $temp_id, $is_new, $is_sell, $type_sell, $cost, $icon_spec);
    $stmt->execute();
    $stmt->close();
    $message = "✅ Thêm mới item thành công!";
}

// --- Cập nhật item_shop đơn ---
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $tab_id = intval($_POST['tab_id']);
    $temp_id = intval($_POST['temp_id']);
    $is_new = intval($_POST['is_new']);
    $is_sell = intval($_POST['is_sell']);
    $type_sell = intval($_POST['type_sell']);
    $cost = intval($_POST['cost']);
    $icon_spec = intval($_POST['icon_spec']);

    $stmt = $conn->prepare("UPDATE item_shop SET tab_id=?, temp_id=?, is_new=?, is_sell=?, type_sell=?, cost=?, icon_spec=? WHERE id=?");
    $stmt->bind_param("iiiiiiii", $tab_id, $temp_id, $is_new, $is_sell, $type_sell, $cost, $icon_spec, $id);
    $stmt->execute();
    $stmt->close();
    $message = "📝 Cập nhật thành công item có ID $id!";
}

// --- Cập nhật hàng loạt tab_id ---
if (isset($_POST['bulk_update_tab'])) {
    $old_tab = intval($_POST['old_tab']);
    $new_tab = intval($_POST['new_tab']);

    $stmt = $conn->prepare("UPDATE item_shop SET tab_id=? WHERE tab_id=?");
    $stmt->bind_param("ii", $new_tab, $old_tab);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    $message = "🔁 Đã cập nhật $affected item từ Tab $old_tab sang Tab $new_tab!";
}

// --- Xóa item_shop ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM item_shop WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $message = "❌ Đã xóa item có ID $id!";
}

// --- Hiển thị danh sách ---
$result = $conn->query("SELECT * FROM item_shop ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Item Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .message {
            background: #e9ffe9;
            border: 1px solid #28a745;
            padding: 10px;
            margin-bottom: 15px;
            color: #155724;
            border-radius: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 5px #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        input[type="number"], input[type="text"] {
            width: 90%;
            padding: 4px;
        }
        button {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            background: #28a745;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        a.delete {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        a.delete:hover {
            text-decoration: underline;
        }
        .bulk-box {
            margin: 15px 0;
            padding: 12px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 4px #ccc;
        }
    </style>
</head>
<body>

<h1>🛒 Quản lý Item Shop</h1>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- Cập nhật hàng loạt tab_id -->
<div class="bulk-box">
    <h3>🔁 Cập nhật hàng loạt Tab ID</h3>
    <form method="post">
        Tab ID cũ: <input type="number" name="old_tab" required>
        👉
        Tab ID mới: <input type="number" name="new_tab" required>
        <button type="submit" name="bulk_update_tab">Cập nhật</button>
    </form>
</div>

<!-- Thêm mới -->
<h2>➕ Thêm mới Item Shop</h2>
<form method="post">
    <table>
        <tr>
            <th>ID</th>
            <th>Tab ID</th>
            <th>Temp ID</th>
            <th>Is New</th>
            <th>Is Sell</th>
            <th>Type Sell</th>
            <th>Cost</th>
            <th>Icon Spec</th>
            <th>Hành động</th>
        </tr>
        <tr>
            <td><input type="number" name="id" required></td>
            <td><input type="number" name="tab_id" value="0"></td>
            <td><input type="number" name="temp_id" value="0"></td>
            <td><input type="number" name="is_new" value="1"></td>
            <td><input type="number" name="is_sell" value="1"></td>
            <td><input type="number" name="type_sell" value="1"></td>
            <td><input type="number" name="cost" value="0"></td>
            <td><input type="number" name="icon_spec" value="0"></td>
            <td><button type="submit" name="add">Thêm</button></td>
        </tr>
    </table>
</form>

<!-- Danh sách -->
<h2>📜 Danh sách Item Shop</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Tab ID</th>
        <th>Temp ID</th>
        <th>Is New</th>
        <th>Is Sell</th>
        <th>Type Sell</th>
        <th>Cost</th>
        <th>Icon Spec</th>
        <th>Hành động</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
                <td><input type="number" name="tab_id" value="<?= $row['tab_id'] ?>"></td>
                <td><input type="number" name="temp_id" value="<?= $row['temp_id'] ?>"></td>
                <td><input type="number" name="is_new" value="<?= $row['is_new'] ?>"></td>
                <td><input type="number" name="is_sell" value="<?= $row['is_sell'] ?>"></td>
                <td><input type="number" name="type_sell" value="<?= $row['type_sell'] ?>"></td>
                <td><input type="number" name="cost" value="<?= $row['cost'] ?>"></td>
                <td><input type="number" name="icon_spec" value="<?= $row['icon_spec'] ?>"></td>
                <td>
                    <button type="submit" name="update">Cập nhật</button>
                    <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Xóa item ID <?= $row['id'] ?>?')">Xóa</a>
                </td>
            </form>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
