<?php
require_once "config.php";

// --- Thêm mới item_shop_option ---
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    $item_shop_id = $_POST['item_shop_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("INSERT INTO item_shop_option (id, item_shop_id, option_id, param) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $id, $item_shop_id, $option_id, $param);
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
}

// --- Cập nhật item_shop_option ---
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $item_shop_id = $_POST['item_shop_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("UPDATE item_shop_option SET item_shop_id=?, option_id=?, param=? WHERE id=?");
    $stmt->bind_param("iiii", $item_shop_id, $option_id, $param, $id);
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
}

// --- Xóa item_shop_option ---
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM item_shop_option WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
}

// --- Tìm kiếm ---
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM item_shop_option WHERE id LIKE ? OR item_shop_id LIKE ? ORDER BY id ASC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM item_shop_option ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Item Shop Option</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .loading {
            color: #007bff;
        }
    </style>
</head>
<body><link rel="stylesheet" href="admin-style.css">
<h1>Quản lý Item Shop Option</h1>

<div id="message"></div>

<form method="get" id="searchForm">
    Tìm kiếm ID hoặc Item Shop ID: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Tìm</button>
</form>

<h2>Thêm mới Item Shop Option</h2>
<form method="post" id="addForm">
    ID: <input type="number" name="id" required><br>
    Item Shop ID: <input type="number" name="item_shop_id" value="0"><br>
    Option ID: <input type="number" name="option_id" value="0"><br>
    Param: <input type="number" name="param" value="0"><br>
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách Item Shop Option</h2>
<table border="1" cellpadding="5" id="dataTable">
    <tr>
        <th>ID</th>
        <th>Item Shop ID</th>
        <th>Option ID</th>
        <th>Param</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr id="row_<?= $row['id'] ?>">
        <form method="post" class="updateForm">
            <td><input type="number" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="number" name="item_shop_id" value="<?= $row['item_shop_id'] ?>"></td>
            <td><input type="number" name="option_id" value="<?= $row['option_id'] ?>"></td>
            <td><input type="number" name="param" value="<?= $row['param'] ?>"></td>
            <td>
                <button type="submit" name="update">Cập nhật</button>
                <a href="#" class="deleteBtn" data-id="<?= $row['id'] ?>">Xóa</a>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<script>
$(document).ready(function() {
    // Hiển thị thông báo
    function showMessage(message, type) {
        var messageDiv = $('#message');
        messageDiv.removeClass('success error').addClass(type);
        messageDiv.html(message).show();
        setTimeout(function() {
            messageDiv.fadeOut();
        }, 3000);
    }

    // Xử lý thêm mới
    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response === 'success') {
                    showMessage('Thêm mới thành công!', 'success');
                    $('#addForm')[0].reset();
                    // Reload lại trang để hiển thị dữ liệu mới
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showMessage('Có lỗi xảy ra khi thêm mới!', 'error');
                }
            }
        });
    });

    // Xử lý cập nhật
    $('.updateForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var row = $(this).closest('tr');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response === 'success') {
                    showMessage('Cập nhật thành công!', 'success');
                    // Có thể thêm hiệu ứng visual để người dùng biết đã cập nhật
                    row.css('background-color', '#d4edda');
                    setTimeout(function() {
                        row.css('background-color', '');
                    }, 1000);
                } else {
                    showMessage('Có lỗi xảy ra khi cập nhật!', 'error');
                }
            }
        });
    });

    // Xử lý xóa
    $('.deleteBtn').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        
        if(confirm('Bạn có chắc muốn xóa?')) {
            $.ajax({
                url: '?delete=' + id,
                type: 'GET',
                success: function(response) {
                    if(response === 'success') {
                        showMessage('Xóa thành công!', 'success');
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        showMessage('Có lỗi xảy ra khi xóa!', 'error');
                    }
                }
            });
        }
    });

    // Xử lý tìm kiếm (vẫn reload trang để hiển thị kết quả)
    $('#searchForm').on('submit', function(e) {
        // Cho phép form submit bình thường để reload trang với kết quả tìm kiếm
    });
});
</script>
</body>
</html>