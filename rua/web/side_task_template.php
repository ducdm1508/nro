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
    $max_count_lv1 = $_POST['max_count_lv1'];
    $max_count_lv2 = $_POST['max_count_lv2'];
    $max_count_lv3 = $_POST['max_count_lv3'];
    $max_count_lv4 = $_POST['max_count_lv4'];
    $max_count_lv5 = $_POST['max_count_lv5'];

    $stmt = $conn->prepare("INSERT INTO side_task_template (NAME, max_count_lv1, max_count_lv2, max_count_lv3, max_count_lv4, max_count_lv5) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $NAME, $max_count_lv1, $max_count_lv2, $max_count_lv3, $max_count_lv4, $max_count_lv5);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $max_count_lv1 = $_POST['max_count_lv1'];
    $max_count_lv2 = $_POST['max_count_lv2'];
    $max_count_lv3 = $_POST['max_count_lv3'];
    $max_count_lv4 = $_POST['max_count_lv4'];
    $max_count_lv5 = $_POST['max_count_lv5'];

    $stmt = $conn->prepare("UPDATE side_task_template SET NAME=?, max_count_lv1=?, max_count_lv2=?, max_count_lv3=?, max_count_lv4=?, max_count_lv5=? WHERE id=?");
    $stmt->bind_param("ssssssi", $NAME, $max_count_lv1, $max_count_lv2, $max_count_lv3, $max_count_lv4, $max_count_lv5, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM side_task_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị (có tìm kiếm)
if($search != "") {
    $stmt = $conn->prepare("SELECT * FROM side_task_template WHERE NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM side_task_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Side Task Template</title>
</head><link rel="stylesheet" href="admin-style.css">
<body>
<h1>Quản lý Side Task Template</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới</h2>
<form method="post">
    Name:<br> <input type="text" name="NAME" required><br>
    Max Count LV1:<br> <input type="text" name="max_count_lv1" required><br>
    Max Count LV2:<br> <input type="text" name="max_count_lv2" required><br>
    Max Count LV3:<br> <input type="text" name="max_count_lv3" required><br>
    Max Count LV4:<br> <input type="text" name="max_count_lv4" required><br>
    Max Count LV5:<br> <input type="text" name="max_count_lv5" required><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Side Task Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Name</th><th>LV1</th><th>LV2</th><th>LV3</th><th>LV4</th><th>LV5</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><input type="text" name="max_count_lv1" value="<?= $row['max_count_lv1'] ?>"></td>
<td><input type="text" name="max_count_lv2" value="<?= $row['max_count_lv2'] ?>"></td>
<td><input type="text" name="max_count_lv3" value="<?= $row['max_count_lv3'] ?>"></td>
<td><input type="text" name="max_count_lv4" value="<?= $row['max_count_lv4'] ?>"></td>
<td><input type="text" name="max_count_lv5" value="<?= $row['max_count_lv5'] ?>"></td>
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
