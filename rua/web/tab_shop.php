<?php
require_once "config.php";

// Xử lý tìm kiếm
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Thêm mới
if(isset($_POST['add'])) {
    $shop_id = $_POST['shop_id'];
    $NAME = $_POST['NAME'];

    $stmt = $conn->prepare("INSERT INTO tab_shop (shop_id, NAME) VALUES (?, ?)");
    $stmt->bind_param("is", $shop_id, $NAME);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $shop_id = $_POST['shop_id'];
    $NAME = $_POST['NAME'];

    $stmt = $conn->prepare("UPDATE tab_shop SET shop_id=?, NAME=? WHERE id=?");
    $stmt->bind_param("isi", $shop_id, $NAME, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tab_shop WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị (có tìm kiếm)
if($search != "") {
    $stmt = $conn->prepare("SELECT * FROM tab_shop WHERE NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM tab_shop ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Tab Shop</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Tab Shop</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới</h2>
<form method="post">
    Shop ID:<br> <input type="number" name="shop_id" required><br>
    Name:<br> <input type="text" name="NAME" required><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Tab Shop</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Shop ID</th><th>Name</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="number" name="shop_id" value="<?= $row['shop_id'] ?>"></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
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
