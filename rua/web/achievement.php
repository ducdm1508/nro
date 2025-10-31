<?php
include 'config.php';

// Xử lý thêm mới
if (isset($_POST['add'])) {
    $info1 = $_POST['info1'];
    $info2 = $_POST['info2'];
    $money = $_POST['money'];
    $max_count = $_POST['max_count'];

    $sql = "INSERT INTO achievement_template (info1, info2, money, max_count) 
            VALUES ('$info1', '$info2', $money, $max_count)";
    $conn->query($sql);
}

// Xử lý cập nhật
if (isset($_POST['update'])) {
    $id        = $_POST['id'];
    $info1     = $_POST['info1'];
    $info2     = $_POST['info2'];
    $money     = $_POST['money'];
    $max_count = $_POST['max_count'];

    $sql = "UPDATE achievement_template 
            SET info1='$info1', info2='$info2', money=$money, max_count=$max_count 
            WHERE id=$id";
    $conn->query($sql);
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM achievement_template WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM achievement_template 
                            WHERE info1 LIKE '%$search%' OR info2 LIKE '%$search%' 
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM achievement_template ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Achievement Template</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;}
        table { border-collapse: collapse; width: 100%; margin-top: 20px;}
        table, th, td { border: 1px solid #ddd; padding: 8px;}
        th { background: #f2f2f2;}
        input[type="text"], input[type="number"] { padding: 5px; width: 150px;}
        textarea { width: 200px; height: 60px;}
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<h2>Quản lý bảng Achievement Template</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm Achievement</h3>
    Info1: <textarea name="info1" required></textarea>
    Info2: <textarea name="info2" required></textarea>
    Money: <input type="number" name="money" required>
    Max Count: <input type="number" name="max_count" required>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm theo info1/info2..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách achievement -->
<table>
    <tr>
        <th>ID</th>
        <th>Info1</th>
        <th>Info2</th>
        <th>Money</th>
        <th>Max Count</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><textarea name="info1"><?= $row['info1'] ?></textarea></td>
            <td><textarea name="info2"><?= $row['info2'] ?></textarea></td>
            <td><input type="number" name="money" value="<?= $row['money'] ?>"></td>
            <td><input type="number" name="max_count" value="<?= $row['max_count'] ?>"></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="achievement.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa bản ghi này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
