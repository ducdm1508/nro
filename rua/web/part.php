<?php
require_once "config.php";

// Xử lý tìm kiếm
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

// Add
if(isset($_POST['add'])){
    $id = $_POST['id'];
    $type = $_POST['TYPE'];
    $data = $_POST['DATA'];
    $stmt = $conn->prepare("INSERT INTO part (id, TYPE, DATA) VALUES (?, ?, ?)");
    $stmt->bind_param("iis",$id,$type,$data);
    $stmt->execute(); $stmt->close();
}

// Update
if(isset($_POST['update'])){
    $old_id = $_POST['old_id']; // ID cũ để WHERE
    $id = $_POST['id'];          // ID mới
    $type = $_POST['TYPE'];
    $data = $_POST['DATA'];
    $stmt = $conn->prepare("UPDATE part SET id=?, TYPE=?, DATA=? WHERE id=?");
    $stmt->bind_param("iisi",$id,$type,$data,$old_id);
    $stmt->execute(); $stmt->close();
}

// Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM part WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute(); $stmt->close();
}

// Fetch all (có tìm kiếm)
if($search != ""){
    $stmt = $conn->prepare("SELECT * FROM part WHERE DATA LIKE ? OR TYPE LIKE ? OR id LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("sss",$like,$like,$like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM part ORDER BY id ASC");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Part Management</title>
<link rel="stylesheet" href="admin-style.css">
</head>
<body>
<h1>Quản lý Part</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm ID/TYPE/DATA: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới Part</h2>
<form method="post">
<label>ID:</label><br>
<input type="number" name="id" required><br>
<label>TYPE:</label><br>
<input type="number" name="TYPE" required><br>
<label>DATA (JSON/text):</label><br>
<textarea name="DATA" rows="5" cols="50" required></textarea><br>
<button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Part</h2>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>TYPE</th><th>DATA</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<form method="post">
<td>
<input type="hidden" name="old_id" value="<?= $row['id'] ?>">
<input type="number" name="id" value="<?= $row['id'] ?>">
</td>
<td><input type="number" name="TYPE" value="<?= $row['TYPE'] ?>"></td>
<td><textarea name="DATA" rows="5" cols="50"><?= htmlspecialchars($row['DATA']) ?></textarea></td>
<td>
<button type="submit" name="update">Cập nhật</button>
<a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa Part này?')">Xóa</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Quay lại menu</a>
</body>
</html>
