<?php
require_once "config.php";

// --- Thêm mới ---
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    $TYPE = $_POST['TYPE'];
    $NAME = $_POST['NAME'];
    $hp = $_POST['hp'];
    $range_move = $_POST['range_move'];
    $speed = $_POST['speed'];
    $dart_type = $_POST['dart_type'];
    $percent_dame = $_POST['percent_dame'];
    $percent_tiem_nang = $_POST['percent_tiem_nang'];

    $stmt = $conn->prepare("INSERT INTO mob_template 
    (id, TYPE, NAME, hp, range_move, speed, dart_type, percent_dame, percent_tiem_nang)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiiiii", $id, $TYPE, $NAME, $hp, $range_move, $speed, $dart_type, $percent_dame, $percent_tiem_nang);
    $stmt->execute();
    $stmt->close();
}

// --- Cập nhật ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $TYPE = $_POST['TYPE'];
    $NAME = $_POST['NAME'];
    $hp = $_POST['hp'];
    $range_move = $_POST['range_move'];
    $speed = $_POST['speed'];
    $dart_type = $_POST['dart_type'];
    $percent_dame = $_POST['percent_dame'];
    $percent_tiem_nang = $_POST['percent_tiem_nang'];

    $stmt = $conn->prepare("UPDATE mob_template SET TYPE=?, NAME=?, hp=?, range_move=?, speed=?, dart_type=?, percent_dame=?, percent_tiem_nang=? WHERE id=?");
    $stmt->bind_param("isiiiiiii", $TYPE, $NAME, $hp, $range_move, $speed, $dart_type, $percent_dame, $percent_tiem_nang, $id);
    $stmt->execute();
    $stmt->close();
}

// --- Xóa ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM mob_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- Tìm kiếm ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM mob_template WHERE id LIKE ? OR NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM mob_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Mob Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Mob Template</h1>

<form method="get">
    Tìm kiếm ID hoặc Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới Mob Template</h2>
<form method="post">
    ID: <input type="number" name="id" required><br>
    Type: <input type="number" name="TYPE" value="1"><br>
    Name: <input type="text" name="NAME" required><br>
    HP: <input type="number" name="hp" value="100"><br>
    Range Move: <input type="number" name="range_move" value="1"><br>
    Speed: <input type="number" name="speed" value="1"><br>
    Dart Type: <input type="number" name="dart_type" value="1"><br>
    Percent Dame: <input type="number" name="percent_dame" value="5"><br>
    Percent Tiem Nang: <input type="number" name="percent_tiem_nang" value="50"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Mob Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Type</th><th>Name</th><th>HP</th><th>Range Move</th><th>Speed</th><th>Dart Type</th><th>%Dame</th><th>%Tiem Nang</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="number" name="TYPE" value="<?= $row['TYPE'] ?>"></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><input type="number" name="hp" value="<?= $row['hp'] ?>"></td>
<td><input type="number" name="range_move" value="<?= $row['range_move'] ?>"></td>
<td><input type="number" name="speed" value="<?= $row['speed'] ?>"></td>
<td><input type="number" name="dart_type" value="<?= $row['dart_type'] ?>"></td>
<td><input type="number" name="percent_dame" value="<?= $row['percent_dame'] ?>"></td>
<td><input type="number" name="percent_tiem_nang" value="<?= $row['percent_tiem_nang'] ?>"></td>
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
