<?php
include 'config.php';

// Thêm event
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $data = $_POST['data'];

    $sql = "INSERT INTO event (name, data) VALUES ('$name', '$data')";
    $conn->query($sql);
}

// Cập nhật event
if (isset($_POST['update'])) {
    $id   = $_POST['id'];
    $name = $_POST['name'];
    $data = $_POST['data'];

    $sql = "UPDATE event SET name='$name', data='$data' WHERE id=$id";
    $conn->query($sql);
}

// Xóa event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM event WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM event 
                            WHERE name LIKE '%$search%' OR data LIKE '%$search%'
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM event ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Event</title>
    <style>
        body { font-family: Arial, sans-serif; margin:20px;}
        table { border-collapse: collapse; width:100%; margin-top:20px;}
        th, td { border:1px solid #ddd; padding:8px; vertical-align: top;}
        th { background:#f2f2f2;}
        input, textarea { width: 95%;}
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
<link rel="stylesheet" href="admin-style.css">
<h2>Quản lý Sự kiện</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm sự kiện mới</h3>
    Tên sự kiện: <input type="text" name="name" required><br><br>
    Dữ liệu (JSON/text):<br>
    <textarea name="data" rows="5" required></textarea><br><br>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm theo tên hoặc dữ liệu..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Dữ liệu</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
            <td><textarea name="data" rows="5"><?= htmlspecialchars($row['data']) ?></textarea></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="event.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa sự kiện này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
