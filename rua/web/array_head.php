<?php
include 'config.php';

// Xử lý thêm mới
if (isset($_POST['add'])) {
    $data = $_POST['data'];

    $sql = "INSERT INTO array_head_2_frames (data) VALUES ('$data')";
    $conn->query($sql);
}

// Xử lý cập nhật
if (isset($_POST['update'])) {
    $id   = $_POST['id'];
    $data = $_POST['data'];

    $sql = "UPDATE array_head_2_frames SET data='$data' WHERE id=$id";
    $conn->query($sql);
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM array_head_2_frames WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM array_head_2_frames 
                            WHERE data LIKE '%$search%' 
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM array_head_2_frames ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Array Head 2 Frames</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;}
        table { border-collapse: collapse; width: 100%; margin-top: 20px;}
        table, th, td { border: 1px solid #ddd; padding: 8px;}
        th { background: #f2f2f2;}
        textarea { width: 400px; height: 80px;}
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
<link rel="stylesheet" href="admin-style.css">
<h2>Quản lý bảng Array Head 2 Frames</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm Frame</h3>
    Data (JSON/text): <br>
    <textarea name="data" required></textarea><br><br>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm trong data..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><textarea name="data"><?= htmlspecialchars($row['data']) ?></textarea></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="array_head.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa frame này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
