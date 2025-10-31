<?php
require_once "config.php";

// Xử lý AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    $action = $_POST['action'] ?? '';

    if ($action == "add") {
        $id = $_POST['id'] ?? '';
        $name = $_POST['NAME'] ?? '';

        if ($id != "") {
            // Thêm với id thủ công
            $stmt = $conn->prepare("INSERT INTO item_option_template (id, NAME) VALUES (?, ?)");
            $stmt->bind_param("is", $id, $name);
        } else {
            // Nếu không nhập id -> để MySQL tự tăng
            $stmt = $conn->prepare("INSERT INTO item_option_template (NAME) VALUES (?)");
            $stmt->bind_param("s", $name);
        }
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Thêm thành công"]);
        exit;
    }

    if ($action == "update") {
        $id = $_POST['id'] ?? '';
        $name = $_POST['NAME'] ?? '';

        $stmt = $conn->prepare("UPDATE item_option_template SET NAME=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Cập nhật thành công"]);
        exit;
    }

    if ($action == "delete") {
        $id = $_POST['id'] ?? '';

        $stmt = $conn->prepare("DELETE FROM item_option_template WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Xóa thành công"]);
        exit;
    }
}

// ---------------------------
// Lấy danh sách (có phân trang)
// ---------------------------
$search = $_GET['search'] ?? "";
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit  = 500; // số dòng mỗi trang
$offset = ($page - 1) * $limit;

// Query dữ liệu
if ($search != "") {
    $stmt = $conn->prepare("SELECT * FROM item_option_template 
                            WHERE id LIKE ? OR NAME LIKE ? 
                            ORDER BY id ASC 
                            LIMIT ? OFFSET ?");
    $like = "%$search%";
    $stmt->bind_param("ssii", $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy tổng số bản ghi cho phân trang
    $stmt2 = $conn->prepare("SELECT COUNT(*) as c FROM item_option_template WHERE id LIKE ? OR NAME LIKE ?");
    $stmt2->bind_param("ss", $like, $like);
    $stmt2->execute();
    $total = $stmt2->get_result()->fetch_assoc()['c'];
    $stmt2->close();
} else {
    $stmt = $conn->prepare("SELECT * FROM item_option_template 
                            ORDER BY id ASC 
                            LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy tổng số bản ghi
    $resCount = $conn->query("SELECT COUNT(*) as c FROM item_option_template");
    $total = $resCount->fetch_assoc()['c'];
}

$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Item Option Template</title>
    <link rel="stylesheet" href="admin-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h1>Quản lý Item Option Template</h1>

<!-- Form tìm kiếm -->
<form method="get">
    Tìm kiếm ID hoặc NAME: 
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<!-- Form thêm mới -->
<h2>Thêm mới Option Template</h2>
<form id="addForm">
    ID (thủ công, có thể bỏ trống): <input type="number" name="id"><br>
    Name: <input type="text" name="NAME" required><br>
    <button type="submit">Thêm</button>
</form>

<!-- Danh sách option template -->
<h2>Danh sách Option Template (Trang <?= $page ?>/<?= $totalPages ?>)</h2>
<table border="1" cellpadding="5" id="optionTable">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr data-id="<?= $row['id'] ?>">
        <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
        <td><input type="text" name="NAME" value="<?= htmlspecialchars($row['NAME']) ?>"></td>
        <td>
            <button class="updateBtn">Cập nhật</button>
            <button class="deleteBtn">Xóa</button>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- Phân trang -->
<div style="margin-top: 10px;">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">&laquo; Trước</a>
    <?php endif; ?>

    <?php for ($i=1; $i <= $totalPages; $i++): ?>
        <?php if ($i == $page): ?>
            <strong><?= $i ?></strong>
        <?php else: ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Sau &raquo;</a>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Thêm mới
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $.post("item_option_template.php", $(this).serialize() + "&action=add", function(res) {
            alert(res.message);
            location.reload();
        }, "json");
    });

    // Cập nhật
    $(".updateBtn").click(function(e) {
        e.preventDefault();
        let row = $(this).closest("tr");
        let id = row.find("input[name='id']").val();
        let name = row.find("input[name='NAME']").val();

        $.post("item_option_template.php", {action:"update", id:id, NAME:name}, function(res) {
            alert(res.message);
        }, "json");
    });

    // Xóa
    $(".deleteBtn").click(function(e) {
        e.preventDefault();
        if (!confirm("Bạn có chắc muốn xóa?")) return;
        let row = $(this).closest("tr");
        let id = row.find("input[name='id']").val();

        $.post("item_option_template.php", {action:"delete", id:id}, function(res) {
            alert(res.message);
            location.reload();
        }, "json");
    });
});
</script>

</body>
</html>
