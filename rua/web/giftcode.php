<?php
include 'config.php';

// Thêm giftcode
if (isset($_POST['add'])) {
    $code       = $_POST['code'];
    $count_left = $_POST['count_left'];
    $detail     = $_POST['detail'];
    $expired    = $_POST['expired'];

    $sql = "INSERT INTO giftcode (code, count_left, detail, expired) 
            VALUES ('$code', $count_left, '$detail', '$expired')";
    $conn->query($sql);
}

// Cập nhật giftcode
if (isset($_POST['update'])) {
    $id         = $_POST['id'];
    $code       = $_POST['code'];
    $count_left = $_POST['count_left'];
    $detail     = $_POST['detail'];
    $expired    = $_POST['expired'];

    $sql = "UPDATE giftcode 
            SET code='$code', count_left=$count_left, detail='$detail', expired='$expired'
            WHERE id=$id";
    $conn->query($sql);
}

// Xóa giftcode
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM giftcode WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM giftcode 
                            WHERE code LIKE '%$search%' OR detail LIKE '%$search%'
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM giftcode ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Giftcode</title>
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
<h2>Quản lý Giftcode</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm Giftcode mới</h3>
    Code: <input type="text" name="code" required><br><br>
    Lượt còn lại: <input type="number" name="count_left" value="1" required><br><br>
    Chi tiết: <textarea name="detail" rows="3" required></textarea><br><br>
    Ngày hết hạn: <input type="datetime-local" name="expired"><br><br>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm theo code hoặc chi tiết..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Lượt còn lại</th>
        <th>Chi tiết</th>
        <th>Ngày tạo</th>
        <th>Ngày hết hạn</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="code" value="<?= htmlspecialchars($row['code']) ?>"></td>
            <td><input type="number" name="count_left" value="<?= $row['count_left'] ?>"></td>
            <td><textarea name="detail" rows="3"><?= htmlspecialchars($row['detail']) ?></textarea></td>
            <td><?= $row['datecreate'] ?></td>
            <td><input type="datetime-local" name="expired" value="<?= str_replace(' ', 'T', $row['expired']) ?>"></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="giftcode.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa giftcode này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
