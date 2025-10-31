<?php
require_once "config.php";

// Xử lý tìm kiếm
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Thêm mới
if(isset($_POST['add'])) {
    $task_main_id = $_POST['task_main_id'];
    $NAME = $_POST['NAME'];
    $max_count = $_POST['max_count'];
    $notify = $_POST['notify'];
    $npc_id = $_POST['npc_id'];
    $map = $_POST['map'];
    $ducvupro = $_POST['ducvupro'];

    $stmt = $conn->prepare("INSERT INTO task_sub_template (task_main_id, NAME, max_count, notify, npc_id, map, ducvupro) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiiii", $task_main_id, $NAME, $max_count, $notify, $npc_id, $map, $ducvupro);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $task_main_id = $_POST['task_main_id'];
    $NAME = $_POST['NAME'];
    $max_count = $_POST['max_count'];
    $notify = $_POST['notify'];
    $npc_id = $_POST['npc_id'];
    $map = $_POST['map'];
    $ducvupro = $_POST['ducvupro'];

    $stmt = $conn->prepare("UPDATE task_sub_template SET NAME=?, max_count=?, notify=?, npc_id=?, map=?, ducvupro=? WHERE task_main_id=?");
    $stmt->bind_param("siiiiii", $NAME, $max_count, $notify, $npc_id, $map, $ducvupro, $task_main_id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $task_main_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM task_sub_template WHERE task_main_id=?");
    $stmt->bind_param("i", $task_main_id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị (có tìm kiếm)
if($search != "") {
    $stmt = $conn->prepare("SELECT * FROM task_sub_template WHERE NAME LIKE ? ORDER BY task_main_id ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM task_sub_template ORDER BY task_main_id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Task Sub Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Task Sub Template</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới</h2>
<form method="post">
    Task Main ID:<br> <input type="number" name="task_main_id" required><br>
    Name:<br> <input type="text" name="NAME" required><br>
    Max Count:<br> <input type="number" name="max_count" value="-1"><br>
    Notify:<br> <input type="text" name="notify" value=""><br>
    NPC ID:<br> <input type="number" name="npc_id" value="-1"><br>
    Map:<br> <input type="number" name="map" required><br>
    Ducvupro:<br> <input type="number" name="ducvupro" required><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Task Sub Template</h2>
<table border="1" cellpadding="5">
<tr>
<th>Task Main ID</th><th>Name</th><th>Max Count</th><th>Notify</th><th>NPC ID</th><th>Map</th><th>Ducvupro</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="task_main_id" value="<?= $row['task_main_id'] ?>" readonly></td>
<td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
<td><input type="number" name="max_count" value="<?= $row['max_count'] ?>"></td>
<td><input type="text" name="notify" value="<?= $row['notify'] ?>"></td>
<td><input type="number" name="npc_id" value="<?= $row['npc_id'] ?>"></td>
<td><input type="number" name="map" value="<?= $row['map'] ?>"></td>
<td><input type="number" name="ducvupro" value="<?= $row['ducvupro'] ?>"></td>
<td>
<button type="submit" name="update">Cập nhật</button>
<a href="?delete=<?= $row['task_main_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
