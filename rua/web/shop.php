<?php
require_once "config.php";

// Thêm mới
if(isset($_POST['add'])) {
    $npc_id = $_POST['npc_id'];
    $tag_name = $_POST['tag_name'];
    $type_shop = $_POST['type_shop'];

    $stmt = $conn->prepare("INSERT INTO shop (npc_id, tag_name, type_shop) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $npc_id, $tag_name, $type_shop);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $npc_id = $_POST['npc_id'];
    $tag_name = $_POST['tag_name'];
    $type_shop = $_POST['type_shop'];

    $stmt = $conn->prepare("UPDATE shop SET npc_id=?, tag_name=?, type_shop=? WHERE id=?");
    $stmt->bind_param("isii", $npc_id, $tag_name, $type_shop, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM shop WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị
$result = $conn->query("SELECT * FROM shop ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Shop</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Shop</h1>

<h2>Thêm mới Shop</h2>
<form method="post">
    NPC ID:<br> <input type="number" name="npc_id" required><br>
    Tag Name:<br> <input type="text" name="tag_name"><br>
    Type Shop:<br> <input type="number" name="type_shop"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Shop</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>NPC ID</th><th>Tag Name</th><th>Type Shop</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="number" name="npc_id" value="<?= $row['npc_id'] ?>"></td>
<td><input type="text" name="tag_name" value="<?= $row['tag_name'] ?>"></td>
<td><input type="number" name="type_shop" value="<?= $row['type_shop'] ?>"></td>
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
