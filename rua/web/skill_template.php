<?php
require_once "config.php";

// Xử lý tìm kiếm
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Thêm mới
if(isset($_POST['add'])) {
    $nclass_id = $_POST['nclass_id'];
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $max_point = $_POST['max_point'];
    $mana_use_type = $_POST['mana_use_type'];
    $TYPE = $_POST['TYPE'];
    $icon_id = $_POST['icon_id'];
    $dam_info = $_POST['dam_info'];
    $slot = $_POST['slot'];
    $skills = $_POST['skills'];

    $stmt = $conn->prepare("INSERT INTO skill_template (nclass_id, id, NAME, max_point, mana_use_type, TYPE, icon_id, dam_info, slot, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiiiiiss", $nclass_id, $id, $NAME, $max_point, $mana_use_type, $TYPE, $icon_id, $dam_info, $slot, $skills);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $nclass_id = $_POST['nclass_id'];
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $max_point = $_POST['max_point'];
    $mana_use_type = $_POST['mana_use_type'];
    $TYPE = $_POST['TYPE'];
    $icon_id = $_POST['icon_id'];
    $dam_info = $_POST['dam_info'];
    $slot = $_POST['slot'];
    $skills = $_POST['skills'];

    $stmt = $conn->prepare("UPDATE skill_template SET nclass_id=?, NAME=?, max_point=?, mana_use_type=?, TYPE=?, icon_id=?, dam_info=?, slot=?, skills=? WHERE id=?");
    $stmt->bind_param("isiiiiissi", $nclass_id, $NAME, $max_point, $mana_use_type, $TYPE, $icon_id, $dam_info, $slot, $skills, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM skill_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị (có tìm kiếm)
if($search != "") {
    $stmt = $conn->prepare("SELECT * FROM skill_template WHERE NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM skill_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Skill Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Skill Template</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới</h2>
<form method="post">
    NClass ID:<br> <input type="number" name="nclass_id" required><br>
    ID:<br> <input type="number" name="id" required><br>
    Name:<br> <input type="text" name="NAME" required><br>
    Max Point:<br> <input type="number" name="max_point" value="7" required><br>
    Mana Use Type:<br> <input type="number" name="mana_use_type" required><br>
    TYPE:<br> <input type="number" name="TYPE" required><br>
    Icon ID:<br> <input type="number" name="icon_id" required><br>
    Dam Info:<br> <input type="text" name="dam_info" required><br>
    Slot:<br> <input type="number" name="slot" required><br>
    Skills:<br> <textarea name="skills"></textarea><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Skill Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>NClass ID</th><th>ID</th><th>Name</th><th>Max Point</th><th>Mana Use Type</th><th>TYPE</th><th>Icon ID</th><th>Dam Info</th><th>Slot</th><th>Skills</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="nclass_id" value="<?= $row['nclass_id'] ?>"></td>
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><input type="number" name="max_point" value="<?= $row['max_point'] ?>"></td>
<td><input type="number" name="mana_use_type" value="<?= $row['mana_use_type'] ?>"></td>
<td><input type="number" name="TYPE" value="<?= $row['TYPE'] ?>"></td>
<td><input type="number" name="icon_id" value="<?= $row['icon_id'] ?>"></td>
<td><input type="text" name="dam_info" value="<?= $row['dam_info'] ?>"></td>
<td><input type="number" name="slot" value="<?= $row['slot'] ?>"></td>
<td><textarea name="skills"><?= $row['skills'] ?></textarea></td>
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
