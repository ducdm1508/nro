<?php
require_once "config.php";

// Thêm mới
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $text = $_POST['text'];
    $stmt = $conn->prepare("INSERT INTO notify (name, text) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $text);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $text = $_POST['text'];
    $stmt = $conn->prepare("UPDATE notify SET name=?, text=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $text, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM notify WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị
$result = $conn->query("SELECT * FROM notify ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Notify</title>
</head>
<body>
<h1>Quản lý Notify</h1>
<link rel="stylesheet" href="admin-style.css">
<h2>Thêm mới Notify</h2>
<form method="post">
    Name:<br> <textarea name="name"></textarea><br>
    Text:<br> <textarea name="text"></textarea><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Notify</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Name</th><th>Text</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><textarea name="name"><?= $row['name'] ?></textarea></td>
<td><textarea name="text"><?= $row['text'] ?></textarea></td>
<td>
<button type="submit" name="update">Cập nhật</button>
<a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
