<?php
include 'config.php';

// Thêm mới
if (isset($_POST['add'])) {
    $image_id = $_POST['image_id'];
    $layer    = $_POST['layer'];
    $dx       = $_POST['dx'];
    $dy       = $_POST['dy'];

    $sql = "INSERT INTO bg_item_template (image_id, layer, dx, dy) 
            VALUES ($image_id, $layer, $dx, $dy)";
    $conn->query($sql);
}

// Cập nhật
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $image_id = $_POST['image_id'];
    $layer    = $_POST['layer'];
    $dx       = $_POST['dx'];
    $dy       = $_POST['dy'];

    $sql = "UPDATE bg_item_template 
            SET image_id=$image_id, layer=$layer, dx=$dx, dy=$dy 
            WHERE id=$id";
    $conn->query($sql);
}

// Xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bg_item_template WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM bg_item_template 
                            WHERE image_id LIKE '%$search%' OR layer LIKE '%$search%' 
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM bg_item_template ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý BG Item Template</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;}
        table { border-collapse: collapse; width: 100%; margin-top: 20px;}
        table, th, td { border: 1px solid #ddd; padding: 8px;}
        th { background: #f2f2f2;}
        input[type="number"] { padding: 5px; width: 100px;}
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
<link rel="stylesheet" href="admin-style.css">
<h2>Quản lý bảng BG Item Template</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm BG Item</h3>
    Image ID: <input type="number" name="image_id" required>
    Layer: <input type="number" name="layer" required>
    DX: <input type="number" name="dx" required>
    DY: <input type="number" name="dy" required>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm theo image_id/layer..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Image ID</th>
        <th>Layer</th>
        <th>DX</th>
        <th>DY</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><input type="number" name="image_id" value="<?= $row['image_id'] ?>"></td>
            <td><input type="number" name="layer" value="<?= $row['layer'] ?>"></td>
            <td><input type="number" name="dx" value="<?= $row['dx'] ?>"></td>
            <td><input type="number" name="dy" value="<?= $row['dy'] ?>"></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="bg_item.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa item này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
