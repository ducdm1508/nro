<?php
require_once "config.php";

// --- Thêm mới ---
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $zones = $_POST['zones'];
    $max_player = $_POST['max_player'];
    $data = $_POST['data'];
    $type = $_POST['type'];
    $planet_id = $_POST['planet_id'];
    $bg_type = $_POST['bg_type'];
    $tile_id = $_POST['tile_id'];
    $bg_id = $_POST['bg_id'];
    $waypoints = $_POST['waypoints'];
    $mobs = $_POST['mobs'];
    $npcs = $_POST['npcs'];
    $is_map_double = $_POST['is_map_double'];

    $stmt = $conn->prepare("INSERT INTO map_template 
    (id, NAME, zones, max_player, data, type, planet_id, bg_type, tile_id, bg_id, waypoints, mobs, npcs, is_map_double)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiiiiiiiisssi", $id, $NAME, $zones, $max_player, $data, $type, $planet_id, $bg_type, $tile_id, $bg_id, $waypoints, $mobs, $npcs, $is_map_double);
    $stmt->execute();
    $stmt->close();
}

// --- Cập nhật ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $NAME = $_POST['NAME'];
    $zones = $_POST['zones'];
    $max_player = $_POST['max_player'];
    $data = $_POST['data'];
    $type = $_POST['type'];
    $planet_id = $_POST['planet_id'];
    $bg_type = $_POST['bg_type'];
    $tile_id = $_POST['tile_id'];
    $bg_id = $_POST['bg_id'];
    $waypoints = $_POST['waypoints'];
    $mobs = $_POST['mobs'];
    $npcs = $_POST['npcs'];
    $is_map_double = $_POST['is_map_double'];

    $stmt = $conn->prepare("UPDATE map_template SET NAME=?, zones=?, max_player=?, data=?, type=?, planet_id=?, bg_type=?, tile_id=?, bg_id=?, waypoints=?, mobs=?, npcs=?, is_map_double=? WHERE id=?");
    $stmt->bind_param("siiiiiiiiisssii", $NAME, $zones, $max_player, $data, $type, $planet_id, $bg_type, $tile_id, $bg_id, $waypoints, $mobs, $npcs, $is_map_double, $id);
    $stmt->execute();
    $stmt->close();
}

// --- Xóa ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM map_template WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- Tìm kiếm ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM map_template WHERE id LIKE ? OR NAME LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM map_template ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Map Template</title>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Map Template</h1>

<form method="get">
    Tìm kiếm ID hoặc Name: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới Map Template</h2>
<form method="post">
    ID: <input type="number" name="id" required><br>
    Name: <input type="text" name="NAME" required><br>
    Zones: <input type="number" name="zones" value="1"><br>
    Max Player: <input type="number" name="max_player" value="15"><br>
    Data: <input type="text" name="data" value="[]"><br>
    Type: <input type="number" name="type" value="1"><br>
    Planet ID: <input type="number" name="planet_id" value="1"><br>
    BG Type: <input type="number" name="bg_type" value="1"><br>
    Tile ID: <input type="number" name="tile_id" value="1"><br>
    BG ID: <input type="number" name="bg_id" value="1"><br>
    Waypoints: <input type="text" name="waypoints" value="[]"><br>
    Mobs: <input type="text" name="mobs" value="[]"><br>
    NPCs: <input type="text" name="npcs" value="[]"><br>
    Is Map Double: <input type="number" name="is_map_double" value="0"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Map Template</h2>
<table border="1" cellpadding="5">
<tr>
    <th>ID</th><th>Name</th><th>Zones</th><th>Max Player</th><th>Data</th><th>Type</th><th>Planet ID</th><th>BG Type</th><th>Tile ID</th><th>BG ID</th><th>Waypoints</th><th>Mobs</th><th>NPCs</th><th>Is Double</th><th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="post">
    <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
    <td><input type="text" name="NAME" value="<?= $row['NAME'] ?>"></td>
    <td><input type="number" name="zones" value="<?= $row['zones'] ?>"></td>
    <td><input type="number" name="max_player" value="<?= $row['max_player'] ?>"></td>
    <td><input type="text" name="data" value="<?= $row['data'] ?>"></td>
    <td><input type="number" name="type" value="<?= $row['type'] ?>"></td>
    <td><input type="number" name="planet_id" value="<?= $row['planet_id'] ?>"></td>
    <td><input type="number" name="bg_type" value="<?= $row['bg_type'] ?>"></td>
    <td><input type="number" name="tile_id" value="<?= $row['tile_id'] ?>"></td>
    <td><input type="number" name="bg_id" value="<?= $row['bg_id'] ?>"></td>
    <td><input type="text" name="waypoints" value="<?= $row['waypoints'] ?>"></td>
    <td><input type="text" name="mobs" value="<?= $row['mobs'] ?>"></td>
    <td><input type="text" name="npcs" value="<?= $row['npcs'] ?>"></td>
    <td><input type="number" name="is_map_double" value="<?= $row['is_map_double'] ?>"></td>
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
