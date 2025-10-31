<?php
require_once "config.php";

// --- Thêm mới intrinsic ---
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    $name = $_POST['NAME'];
    $param_from_1 = $_POST['param_from_1'];
    $param_to_1 = $_POST['param_to_1'];
    $param_from_2 = $_POST['param_from_2'];
    $param_to_2 = $_POST['param_to_2'];
    $icon = $_POST['icon'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("INSERT INTO intrinsic (id, NAME, param_from_1, param_to_1, param_from_2, param_to_2, icon, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiiiii", $id, $name, $param_from_1, $param_to_1, $param_from_2, $param_to_2, $icon, $gender);
    $stmt->execute();
    $stmt->close();
}

// --- Update intrinsic ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['NAME'];
    $param_from_1 = $_POST['param_from_1'];
    $param_to_1 = $_POST['param_to_1'];
    $param_from_2 = $_POST['param_from_2'];
    $param_to_2 = $_POST['param_to_2'];
    $icon = $_POST['icon'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE intrinsic SET NAME=?, param_from_1=?, param_to_1=?, param_from_2=?, param_to_2=?, icon=?, gender=? WHERE id=?");
    $stmt->bind_param("siiiiiii", $name, $param_from_1, $param_to_1, $param_from_2, $param_to_2, $icon, $gender, $id);
    $stmt->execute();
    $stmt->close();
}

// --- Xóa intrinsic ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM intrinsic WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- Tìm kiếm intrinsic ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM intrinsic WHERE id LIKE ? OR NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM intrinsic ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Intrinsic</title>
</head>
<body>
<h1>Quản lý Intrinsic</h1>
<link rel="stylesheet" href="admin-style.css">
<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm ID hoặc Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<!-- Form thêm mới -->
<h2>Thêm mới Intrinsic</h2>
<form method="post">
    ID: <input type="number" name="id" required><br>
    Name: <input type="text" name="NAME" required><br>
    Param From 1: <input type="number" name="param_from_1" value="0"><br>
    Param To 1: <input type="number" name="param_to_1" value="0"><br>
    Param From 2: <input type="number" name="param_from_2" value="0"><br>
    Param To 2: <input type="number" name="param_to_2" value="0"><br>
    Icon: <input type="number" name="icon" value="0"><br>
    Gender: <input type="number" name="gender" value="3"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<!-- Danh sách Intrinsic -->
<h2>Danh sách Intrinsic</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Param 1</th>
        <th>Param 2</th>
        <th>Icon</th>
        <th>Gender</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <form method="post">
            <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
            <td>
                <input type="number" name="param_from_1" value="<?= $row['param_from_1'] ?>"> → 
                <input type="number" name="param_to_1" value="<?= $row['param_to_1'] ?>">
            </td>
            <td>
                <input type="number" name="param_from_2" value="<?= $row['param_from_2'] ?>"> → 
                <input type="number" name="param_to_2" value="<?= $row['param_to_2'] ?>">
            </td>
            <td><input type="number" name="icon" value="<?= $row['icon'] ?>"></td>
            <td><input type="number" name="gender" value="<?= $row['gender'] ?>"></td>
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
