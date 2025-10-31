<?php
include 'config.php';

// Thêm mới
if (isset($_POST['add'])) {
    $head_id   = $_POST['head_id'];
    $avatar_id = $_POST['avatar_id'];
    $conn->query("INSERT INTO head_avatar (head_id, avatar_id) VALUES ($head_id, $avatar_id)");
}

// Cập nhật
if (isset($_POST['update'])) {
    $old_head_id   = $_POST['old_head_id'];
    $old_avatar_id = $_POST['old_avatar_id'];
    $head_id       = $_POST['head_id'];
    $avatar_id     = $_POST['avatar_id'];
    $conn->query("UPDATE head_avatar 
                  SET head_id=$head_id, avatar_id=$avatar_id 
                  WHERE head_id=$old_head_id AND avatar_id=$old_avatar_id");
}

// Xóa
if (isset($_GET['delete'])) {
    $head_id   = $_GET['head_id'];
    $avatar_id = $_GET['avatar_id'];
    $conn->query("DELETE FROM head_avatar WHERE head_id=$head_id AND avatar_id=$avatar_id");
}

// Tìm kiếm
$search = $_GET['search'] ?? '';
$result = $conn->query("SELECT * FROM head_avatar 
                        WHERE head_id LIKE '%$search%' OR avatar_id LIKE '%$search%' 
                        ORDER BY head_id ASC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý Head Avatar</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border:1px solid #ddd; padding:8px; }
th { background:#f2f2f2; }
input { width: 80px; }
.btn { padding:4px 8px; cursor:pointer; }
</style>
</head>
<body>
<link rel="stylesheet" href="admin-style.css">
<h2>Quản lý Head Avatar</h2>

<!-- Form thêm mới -->
<form method="POST">
    Head ID: <input type="number" name="head_id" required>
    Avatar ID: <input type="number" name="avatar_id" required>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm head hoặc avatar..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<table>
<tr><th>Head ID</th><th>Avatar ID</th><th>Thao tác</th></tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <form method="POST">
        <td>
            <input type="hidden" name="old_head_id" value="<?= $row['head_id'] ?>">
            <input type="number" name="head_id" value="<?= $row['head_id'] ?>">
        </td>
        <td>
            <input type="hidden" name="old_avatar_id" value="<?= $row['avatar_id'] ?>">
            <input type="number" name="avatar_id" value="<?= $row['avatar_id'] ?>">
        </td>
        <td>
            <button type="submit" name="update" class="btn">Cập nhật</button>
            <a href="head_avatar.php?delete=1&head_id=<?= $row['head_id'] ?>&avatar_id=<?= $row['avatar_id'] ?>" onclick="return confirm('Xóa?')">Xóa</a>
        </td>
    </form>
</tr>
<?php } ?>
</table>

</body>
</html>
