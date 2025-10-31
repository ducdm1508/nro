<?php
require_once "config.php";

// Lấy danh sách option template để dropdown
$options = $conn->query("SELECT * FROM item_option_template ORDER BY id ASC");

// --- Thêm mới item_options ---
if(isset($_POST['add'])) {
    $item_id = $_POST['item_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("INSERT INTO item_options (item_id, option_id, param) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $item_id, $option_id, $param);
    $stmt->execute();
    $stmt->close();
}

// --- Cập nhật item_options ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $item_id = $_POST['item_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("UPDATE item_options SET item_id=?, option_id=?, param=? WHERE id=?");
    $stmt->bind_param("iiii", $item_id, $option_id, $param, $id);
    $stmt->execute();
    $stmt->close();
}

// --- Xóa item_options ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM item_options WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- Tìm kiếm item_options ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM item_options WHERE id LIKE ? OR item_id LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM item_options ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Item Options</title>
</head>
<body>
<h1>Quản lý Item Options</h1>
<link rel="stylesheet" href="admin-style.css">
<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm ID hoặc Item ID: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<!-- Form thêm mới -->
<h2>Thêm mới Item Option</h2>
<form method="post">
    Item ID: <input type="number" name="item_id" required><br>
    Option: 
    <select name="option_id" required>
        <?php while($opt = $options->fetch_assoc()): ?>
            <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['NAME']) ?></option>
        <?php endwhile; ?>
    </select><br>
    Param: <input type="number" name="param" value="0"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<!-- Danh sách item_options -->
<h2>Danh sách Item Options</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Item ID</th>
        <th>Option</th>
        <th>Param</th>
        <th>Hành động</th>
    </tr>
    <?php
    // Lấy lại danh sách option template để dùng trong bảng
    $options_list = [];
    $opt_res = $conn->query("SELECT * FROM item_option_template");
    while($o = $opt_res->fetch_assoc()){
        $options_list[$o['id']] = $o['NAME'];
    }

    while($row = $result->fetch_assoc()): ?>
    <tr>
        <form method="post">
            <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="number" name="item_id" value="<?= $row['item_id'] ?>"></td>
            <td>
                <select name="option_id" required>
                    <?php foreach($options_list as $opt_id => $opt_name): ?>
                        <option value="<?= $opt_id ?>" <?= $row['option_id']==$opt_id?'selected':'' ?>>
                            <?= htmlspecialchars($opt_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="number" name="param" value="<?= $row['param'] ?>"></td>
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
