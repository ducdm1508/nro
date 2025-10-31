<?php
include 'config.php';

// Thêm clan
if (isset($_POST['add'])) {
    $name     = $_POST['name'];
    $name2    = $_POST['name2'];
    $slogan   = $_POST['slogan'];
    $img_id   = $_POST['img_id'];
    $max_mem  = $_POST['max_member'];

    $sql = "INSERT INTO clan (NAME, NAME_2, slogan, img_id, max_member, members, tops) 
            VALUES ('$name', '$name2', '$slogan', $img_id, $max_mem, '[]', '')";
    $conn->query($sql);
}

// Cập nhật clan
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $name2    = $_POST['name2'];
    $slogan   = $_POST['slogan'];
    $img_id   = $_POST['img_id'];
    $max_mem  = $_POST['max_member'];
    $level    = $_POST['level'];
    $power    = $_POST['power_point'];
    $point    = $_POST['clan_point'];

    $sql = "UPDATE clan SET 
                NAME='$name', 
                NAME_2='$name2', 
                slogan='$slogan', 
                img_id=$img_id, 
                max_member=$max_mem, 
                LEVEL=$level, 
                power_point=$power,
                clan_point=$point
            WHERE id=$id";
    $conn->query($sql);
}

// Xóa clan
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM clan WHERE id=$id");
}

// Tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM clan 
                            WHERE NAME LIKE '%$search%' OR NAME_2 LIKE '%$search%' 
                            ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM clan ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Clan</title>
    <style>
        body { font-family: Arial, sans-serif; margin:20px;}
        table { border-collapse: collapse; width:100%; margin-top:20px;}
        th, td { border:1px solid #ddd; padding:8px;}
        th { background:#f2f2f2;}
        input, textarea { width: 95%;}
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
<link rel="stylesheet" href="admin-style.css">
<h2>Quản lý Clan</h2>

<!-- Form thêm -->
<form method="POST">
    <h3>Thêm Clan</h3>
    Tên: <input type="text" name="name" required><br>
    Tag (tối đa 4 ký tự): <input type="text" name="name2" maxlength="4" required><br>
    Slogan: <input type="text" name="slogan"><br>
    Logo ID: <input type="number" name="img_id" value="0"><br>
    Thành viên tối đa: <input type="number" name="max_member" value="10"><br><br>
    <button type="submit" name="add" class="btn">Thêm</button>
</form>

<!-- Form tìm kiếm -->
<form method="GET">
    <input type="text" name="search" placeholder="Tìm theo tên hoặc tag..." value="<?= $search ?>">
    <button type="submit" class="btn">Tìm kiếm</button>
</form>

<!-- Danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Tag</th>
        <th>Slogan</th>
        <th>Logo</th>
        <th>Power</th>
        <th>Điểm</th>
        <th>Level</th>
        <th>Max Member</th>
        <th>Thành viên</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <form method="POST">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($row['NAME']) ?>"></td>
            <td><input type="text" name="name2" value="<?= htmlspecialchars($row['NAME_2']) ?>" maxlength="4"></td>
            <td><input type="text" name="slogan" value="<?= htmlspecialchars($row['slogan']) ?>"></td>
            <td><input type="number" name="img_id" value="<?= $row['img_id'] ?>"></td>
            <td><input type="number" name="power_point" value="<?= $row['power_point'] ?>"></td>
            <td><input type="number" name="clan_point" value="<?= $row['clan_point'] ?>"></td>
            <td><input type="number" name="level" value="<?= $row['LEVEL'] ?>"></td>
            <td><input type="number" name="max_member" value="<?= $row['max_member'] ?>"></td>
            <td><textarea disabled><?= $row['members'] ?></textarea></td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="update" class="btn">Cập nhật</button>
                <a href="clan.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa clan này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
