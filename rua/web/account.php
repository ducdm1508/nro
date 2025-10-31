<?php
include 'config.php';

// Xử lý thêm mới
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // mã hóa mật khẩu
    $email    = $_POST['email'];

    $sql = "INSERT INTO account (username, password, email) VALUES ('$username', '$password', '$email')";
    $conn->query($sql);
}

// Xử lý cập nhật
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $ban      = $_POST['ban'];
    $is_admin = $_POST['is_admin'];

    $sql = "UPDATE account 
            SET username='$username', email='$email', ban=$ban, is_admin=$is_admin 
            WHERE id=$id";
    $conn->query($sql);
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM account WHERE id=$id");
}

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM account WHERE username LIKE '%$search%' ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM account ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Account</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;}
        table { border-collapse: collapse; width: 100%; margin-top: 20px;}
        table, th, td { border: 1px solid #ddd; padding: 8px;}
        th { background: #f2f2f2;}
        form { margin-bottom: 20px;}
        input[type="text"], input[type="email"], input[type="password"] { padding: 5px; }
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<h2>Quản lý bảng Account</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm Account</h3>
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    Email: <input type="email" name="email" >
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm username..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách account -->
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Ban</th>
        <th>Admin</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="username" value="<?= $row['username'] ?>"></td>
            <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
            <td>
                <select name="ban">
                    <option value="0" <?= $row['ban']==0?"selected":"" ?>>Không</option>
                    <option value="1" <?= $row['ban']==1?"selected":"" ?>>Có</option>
                </select>
            </td>
            <td>
                <select name="is_admin">
                    <option value="0" <?= $row['is_admin']==0?"selected":"" ?>>User</option>
                    <option value="1" <?= $row['is_admin']==1?"selected":"" ?>>Admin</option>
                </select>
            </td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="account.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa tài khoản này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
