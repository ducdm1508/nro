<?php
require_once "config.php";

// Thêm mới
if(isset($_POST['add'])) {
    $player_id = $_POST['player_id'];
    $player_name = $_POST['player_name'];
    $tab = $_POST['tab'];
    $item_id = $_POST['item_id'];
    $gold = $_POST['gold'];
    $gem = $_POST['gem'];
    $quantity = $_POST['quantity'];
    $itemOption = $_POST['itemOption'];
    $lastTime = $_POST['lastTime'];
    $isBuy = $_POST['isBuy'];

    $stmt = $conn->prepare("INSERT INTO shop_ky_gui (player_id, player_name, tab, item_id, gold, gem, quantity, itemOption, lastTime, isBuy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiiiissi", $player_id, $player_name, $tab, $item_id, $gold, $gem, $quantity, $itemOption, $lastTime, $isBuy);
    $stmt->execute();
    $stmt->close();
}

// Cập nhật
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $player_id = $_POST['player_id'];
    $player_name = $_POST['player_name'];
    $tab = $_POST['tab'];
    $item_id = $_POST['item_id'];
    $gold = $_POST['gold'];
    $gem = $_POST['gem'];
    $quantity = $_POST['quantity'];
    $itemOption = $_POST['itemOption'];
    $lastTime = $_POST['lastTime'];
    $isBuy = $_POST['isBuy'];

    $stmt = $conn->prepare("UPDATE shop_ky_gui SET player_id=?, player_name=?, tab=?, item_id=?, gold=?, gem=?, quantity=?, itemOption=?, lastTime=?, isBuy=? WHERE id=?");
    $stmt->bind_param("isiiiiissii", $player_id, $player_name, $tab, $item_id, $gold, $gem, $quantity, $itemOption, $lastTime, $isBuy, $id);
    $stmt->execute();
    $stmt->close();
}

// Xóa
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM shop_ky_gui WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Hiển thị
$result = $conn->query("SELECT * FROM shop_ky_gui ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý Shop Ký Gửi</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Shop Ký Gửi</h1>

<h2>Thêm mới</h2>
<form method="post">
    Player ID:<br> <input type="number" name="player_id" required><br>
    Player Name:<br> <textarea name="player_name"></textarea><br>
    Tab:<br> <input type="number" name="tab" required><br>
    Item ID:<br> <input type="number" name="item_id" required><br>
    Gold:<br> <input type="number" name="gold" required><br>
    Gem:<br> <input type="number" name="gem" required><br>
    Quantity:<br> <input type="number" name="quantity" required><br>
    Item Option:<br> <textarea name="itemOption">[]</textarea><br>
    Last Time:<br> <input type="number" name="lastTime" required><br>
    Is Buy:<br> <input type="number" name="isBuy" required><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Shop Ký Gửi</h2>
<table border="1" cellpadding="5">
<tr>
<th>ID</th><th>Player ID</th><th>Player Name</th><th>Tab</th><th>Item ID</th><th>Gold</th><th>Gem</th><th>Quantity</th><th>Item Option</th><th>Last Time</th><th>Is Buy</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
<td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
<td><input type="number" name="player_id" value="<?= $row['player_id'] ?>"></td>
<td><textarea name="player_name"><?= $row['player_name'] ?></textarea></td>
<td><input type="number" name="tab" value="<?= $row['tab'] ?>"></td>
<td><input type="number" name="item_id" value="<?= $row['item_id'] ?>"></td>
<td><input type="number" name="gold" value="<?= $row['gold'] ?>"></td>
<td><input type="number" name="gem" value="<?= $row['gem'] ?>"></td>
<td><input type="number" name="quantity" value="<?= $row['quantity'] ?>"></td>
<td><textarea name="itemOption"><?= $row['itemOption'] ?></textarea></td>
<td><input type="number" name="lastTime" value="<?= $row['lastTime'] ?>"></td>
<td><input type="number" name="isBuy" value="<?= $row['isBuy'] ?>"></td>
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
