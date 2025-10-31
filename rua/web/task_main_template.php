<?php
require_once "config.php";

// Xử lý tìm kiếm
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Thêm mới
if(isset($_POST['add'])) {
    $NAME = $_POST['NAME'];
    $detail = $_POST['detail'];

    $stmt = $conn->prepare("INSERT INTO task_main_template (NAME, detail) VALUES (?, ?)");
    $stmt->bind_param("ss", $NAME, $detail);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $detail = $_POST['detail'];

    $stmt = $conn->prepare("UPDATE task_main_template SET NAME=?, detail=? WHERE id=?");
    $stmt->bind_param("ssi", $NAME, $detail, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM task_main_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị (có tìm kiếm)
if($search != "") {
    $stmt = $conn->prepare("SELECT * FROM task_main_template WHERE NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM task_main_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Task Main Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Task Main Template</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới</h2>
<form method="post">
    Name:<br> <input type="text" name="NAME" required><br>
    Detail:<br> <textarea name="detail" required></textarea><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Task Main Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Name</th><th>Detail</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><textarea name="detail"><?= $row['detail'] ?></textarea></td>
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
