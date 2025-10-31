<?php
require_once "config.php";

// --- Thêm mới ---
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $head = $_POST['head'];
    $body = $_POST['body'];
    $leg = $_POST['leg'];
    $avatar = $_POST['avatar'];

    $stmt = $conn->prepare("INSERT INTO npc_template (id, NAME, head, body, leg, avatar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiii", $id, $NAME, $head, $body, $leg, $avatar);
    $stmt->execute();
    $stmt->close();
}

// --- Cập nhật ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $head = $_POST['head'];
    $body = $_POST['body'];
    $leg = $_POST['leg'];
    $avatar = $_POST['avatar'];

    $stmt = $conn->prepare("UPDATE npc_template SET NAME=?, head=?, body=?, leg=?, avatar=? WHERE id=?");
    $stmt->bind_param("siiii i", $NAME, $head, $body, $leg, $avatar, $id);
    $stmt->execute();
    $stmt->close();
}

// --- Xóa ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM npc_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- Tìm kiếm ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM npc_template WHERE id LIKE ? OR NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM npc_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý NPC Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý NPC Template</h1>

<form method="get">
    Tìm kiếm ID hoặc Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới NPC Template</h2>
<form method="post">
    ID: <input type="number" name="id" required><br>
    Name: <input type="text" name="NAME" required><br>
    Head: <input type="number" name="head" value="0"><br>
    Body: <input type="number" name="body" value="0"><br>
    Leg: <input type="number" name="leg" value="0"><br>
    Avatar: <input type="number" name="avatar" value="0"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách NPC Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Name</th><th>Head</th><th>Body</th><th>Leg</th><th>Avatar</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><input type="number" name="head" value="<?= $row['head'] ?>"></td>
<td><input type="number" name="body" value="<?= $row['body'] ?>"></td>
<td><input type="number" name="leg" value="<?= $row['leg'] ?>"></td>
<td><input type="number" name="avatar" value="<?= $row['avatar'] ?>"></td>
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
