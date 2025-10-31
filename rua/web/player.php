<?php
require_once "config.php";

// Add
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $head = $_POST['head'];
    $gender = $_POST['gender'];
    $clan_id = $_POST['clan_id'];
    $data_inventory = $_POST['data_inventory'];
    $data_location = $_POST['data_location'];
    $data_point = $_POST['data_point'];
    $skills = $_POST['skills'];
    $stmt = $conn->prepare("INSERT INTO player (name, head, gender, clan_id, data_inventory, data_location, data_point, skills) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("siisssss",$name,$head,$gender,$clan_id,$data_inventory,$data_location,$data_point,$skills);
    $stmt->execute(); $stmt->close();
}

// Update
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $head = $_POST['head'];
    $gender = $_POST['gender'];
    $clan_id = $_POST['clan_id'];
    $data_inventory = $_POST['data_inventory'];
    $data_location = $_POST['data_location'];
    $data_point = $_POST['data_point'];
    $skills = $_POST['skills'];
    $stmt = $conn->prepare("UPDATE player SET head=?, gender=?, clan_id=?, data_inventory=?, data_location=?, data_point=?, skills=? WHERE id=?");
    $stmt->bind_param("iiissssi",$head,$gender,$clan_id,$data_inventory,$data_location,$data_point,$skills,$id);
    $stmt->execute(); $stmt->close();
}

// Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM player WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute(); $stmt->close();
}

// Fetch all
$result = $conn->query("SELECT id, name, head, gender, clan_id, data_inventory, data_location, data_point, skills FROM player ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Player</title>
<link rel="stylesheet" href="admin-style.css">
</head>
<body>
<h1>Quản lý Player</h1>

<h2>Thêm Player mới</h2>
<form method="post">
<label>Name:</label><br>
<input type="text" name="name" required><br>
<label>Head:</label><br>
<input type="number" name="head" value="102"><br>
<label>Gender:</label><br>
<input type="number" name="gender" required><br>
<label>Clan ID:</label><br>
<input type="number" name="clan_id" value="-1"><br>
<label>Data Inventory:</label><br>
<textarea name="data_inventory">[]</textarea><br>
<label>Data Location:</label><br>
<textarea name="data_location">[]</textarea><br>
<label>Data Point:</label><br>
<textarea name="data_point">[]</textarea><br>
<label>Skills:</label><br>
<textarea name="skills">[]</textarea><br>
<button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Player</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Name</th><th>Head</th><th>Gender</th><th>Clan</th><th>Inventory</th><th>Location</th><th>Point</th><th>Skills</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><input type="number" name="head" value="<?= $row['head'] ?>"></td>
<td><input type="number" name="gender" value="<?= $row['gender'] ?>"></td>
<td><input type="number" name="clan_id" value="<?= $row['clan_id'] ?>"></td>
<td><textarea name="data_inventory"><?= htmlspecialchars($row['data_inventory']) ?></textarea></td>
<td><textarea name="data_location"><?= htmlspecialchars($row['data_location']) ?></textarea></td>
<td><textarea name="data_point"><?= htmlspecialchars($row['data_point']) ?></textarea></td>
<td><textarea name="skills"><?= htmlspecialchars($row['skills']) ?></textarea></td>
<td>
<button type="submit" name="update">Cập nhật</button>
<a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa Player này?')">Xóa</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Quay lại menu</a>
</body>
</html>
