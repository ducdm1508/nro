<?php
require_once "config.php";

// --- API: Thêm mới item_shop ---
if (isset($_POST['add']) && !isset($_POST['ajax'])) {
    $tab_id = $_POST['tab_id'];
    $temp_id = $_POST['temp_id'];
    $is_new = $_POST['is_new'];
    $is_sell = $_POST['is_sell'];
    $type_sell = $_POST['type_sell'];
    $cost = $_POST['cost'];

    $icon_query = $conn->prepare("SELECT icon_id FROM item_template WHERE id = ?");
    $icon_query->bind_param("i", $temp_id);
    $icon_query->execute();
    $icon_query->bind_result($icon_id);
    $icon_query->fetch();
    $icon_query->close();

    $stmt = $conn->prepare("INSERT INTO item_shop (tab_id, temp_id, is_new, is_sell, type_sell, cost, icon_spec) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiii", $tab_id, $temp_id, $is_new, $is_sell, $type_sell, $cost, $icon_id);
    $stmt->execute();
    $stmt->close();
}

// --- API: Thêm mới option ---
if (isset($_POST['add_option'])) {
    header('Content-Type: application/json');
    $item_id = $_POST['item_id'];
    $option_id = $_POST['option_id'];
    $param = $_POST['param'];

    $stmt = $conn->prepare("INSERT INTO item_shop_option (item_shop_id, option_id, param) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $item_id, $option_id, $param);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm option thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi thêm option']);
    }
    $stmt->close();
    exit;
}

// --- API: Xóa option ---
if (isset($_GET['delete_option'])) {
    header('Content-Type: application/json');
    $option_id = $_GET['delete_option'];
    if ($conn->query("DELETE FROM item_shop_option WHERE id = $option_id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// --- API: Xóa item_shop ---
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $conn->query("DELETE FROM item_shop WHERE id = $id");
    $conn->query("DELETE FROM item_shop_option WHERE item_shop_id = $id");
    echo json_encode(['success' => true]);
    exit;
}

// --- API: Sửa item_shop ---
if (isset($_POST['edit_item'])) {
    header('Content-Type: application/json');
    $id = $_POST['id'];
    $tab_id = $_POST['tab_id'];
    $is_new = $_POST['is_new'];
    $is_sell = $_POST['is_sell'];
    $type_sell = $_POST['type_sell'];
    $cost = $_POST['cost'];

    $stmt = $conn->prepare("UPDATE item_shop SET tab_id=?, is_new=?, is_sell=?, type_sell=?, cost=? WHERE id=?");
    $stmt->bind_param("iiiiii", $tab_id, $is_new, $is_sell, $type_sell, $cost, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    exit;
}

// --- Lấy dữ liệu ---
$sql = "SELECT s.*, t.name, t.icon_id 
        FROM item_shop s 
        JOIN item_template t ON s.temp_id = t.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Item Shop</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f9f9f9; }
        table { border-collapse: collapse; width: 100%; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background: #4CAF50; color: white; }
        tr:hover { background: #f5f5f5; }
        img { width: 40px; height: 40px; }
        form { margin-bottom: 20px; background: white; padding: 15px; border-radius: 5px; }
        input, button, select { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #4CAF50; color: white; cursor: pointer; border: none; }
        button:hover { background: #45a049; }
        
        .modal {
            display: none; 
            position: fixed; 
            z-index: 10; 
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            overflow-y: auto;
        }
        .modal-content {
            background: #fff; 
            padding: 20px; 
            margin: 5% auto; 
            width: 500px;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .close {
            float: right; 
            font-size: 24px; 
            cursor: pointer;
            font-weight: bold;
        }
        .close:hover { color: red; }
        
        .option-item {
            background: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .option-info { text-align: left; flex: 1; }
        .option-info strong { display: block; }
        .option-info small { color: #666; }
        
        .btn-delete { background: #f44336; padding: 5px 10px; font-size: 12px; }
        .btn-delete:hover { background: #da190b; }
        
        .btn-edit { background: #2196F3; padding: 5px 10px; font-size: 12px; margin-right: 5px; }
        .btn-edit:hover { background: #0b7dda; }
        
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; box-sizing: border-box; }
        
        #optionSuggestions {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .option-suggestion {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        
        .option-suggestion:hover {
            background: #f5f5f5;
        }
        
        .option-suggestion strong { display: block; }
        .option-suggestion small { color: #999; }
        
        h3 { margin-top: 0; }
        
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<h2>🛒 Quản lý Item Shop</h2>

<div id="alertBox"></div>

<div style="margin-bottom: 20px; background: white; padding: 15px; border-radius: 5px;">
    <input type="text" id="searchInput" placeholder="🔍 Tìm kiếm item theo tên, Temp ID, Tab ID..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
</div>

<form method="POST" id="addItemForm">
    <input type="number" name="tab_id" placeholder="Tab ID" required>
    <input type="number" name="temp_id" placeholder="Temp ID" required>
    <input type="number" name="is_new" placeholder="Is New (0/1)" value="1">
    <input type="number" name="is_sell" placeholder="Is Sell (0/1)" value="1">
    <input type="number" name="type_sell" placeholder="Type Sell" value="1">
    <input type="number" name="cost" placeholder="Cost" value="0">
    <button type="submit" name="add">➕ Thêm Item</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Icon</th>
        <th>Tên</th>
        <th>Temp ID</th>
        <th>Tab ID</th>
        <th>Cost</th>
        <th>Is New</th>
        <th>Is Sell</th>
        <th>Type Sell</th>
        <th>Tùy chọn</th>
    </tr>
    <tbody id="itemTableBody">
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr class="item-row" data-id="<?= $row['id'] ?>" data-name="<?= strtolower(htmlspecialchars($row['name'])) ?>" data-temp-id="<?= $row['temp_id'] ?>" data-tab-id="<?= $row['tab_id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><img src="data/icon/x2/<?= $row['icon_id'] ?>.png" alt="icon"></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['temp_id'] ?></td>
            <td><?= $row['tab_id'] ?></td>
            <td class="cost-cell"><?= $row['cost'] ?></td>
            <td class="is_new-cell"><?= $row['is_new'] ?></td>
            <td class="is_sell-cell"><?= $row['is_sell'] ?></td>
            <td class="type_sell-cell"><?= $row['type_sell'] ?></td>
            <td>
                <button type="button" onclick="openModal(<?= $row['id'] ?>)">⚙️ Option</button>
                <button type="button" class="btn-edit" onclick="openEditModal(<?= $row['id'] ?>, <?= $row['tab_id'] ?>, <?= $row['is_new'] ?>, <?= $row['is_sell'] ?>, <?= $row['type_sell'] ?>, <?= $row['cost'] ?>)">✏️ Sửa</button>
                <button type="button" class="btn-delete" onclick="deleteItem(<?= $row['id'] ?>)">🗑️ Xóa</button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal thêm/sửa option -->
<div id="optionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>⚙️ Quản lý Option</h3>
        
        <div id="optionsList"></div>
        
        <hr>
        <h4>Thêm Option Mới</h4>
        <form id="addOptionForm">
            <input type="hidden" name="item_id" id="modal_item_id">
            <div class="form-group">
                <label>Option Template:</label>
                <input type="text" id="searchOption" placeholder="Tìm kiếm hoặc chọn từ danh sách..." autocomplete="off">
                <div id="optionSuggestions" style="border: 1px solid #ddd; max-height: 200px; overflow-y: auto; margin-top: 5px;"></div>
                <input type="hidden" name="option_id" id="modal_option_id" required>
                <div id="selectedOption" style="margin-top: 5px; padding: 8px; background: #e8f5e9; border-radius: 4px; display: none;"></div>
            </div>
            <div class="form-group">
                <label>Param:</label>
                <input type="number" name="param" required>
            </div>
            <button type="submit">➕ Thêm Option</button>
        </form>
    </div>
</div>

<!-- Modal sửa item -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>✏️ Sửa Item</h3>
        <form id="editItemForm">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Tab ID:</label>
                <input type="number" name="tab_id" id="edit_tab_id" required>
            </div>
            <div class="form-group">
                <label>Is New:</label>
                <input type="number" name="is_new" id="edit_is_new" value="1" min="0" max="1">
            </div>
            <div class="form-group">
                <label>Is Sell:</label>
                <input type="number" name="is_sell" id="edit_is_sell" value="1" min="0" max="1">
            </div>
            <div class="form-group">
                <label>Type Sell:</label>
                <input type="number" name="type_sell" id="edit_type_sell" value="1">
            </div>
            <div class="form-group">
                <label>Cost:</label>
                <input type="number" name="cost" id="edit_cost" value="0">
            </div>
            <button type="submit">💾 Lưu Thay Đổi</button>
        </form>
    </div>
</div>

<script>
// Hiển thị alert
function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => {
        alertBox.innerHTML = '';
    }, 3000);
}

// Thêm item
document.getElementById('addItemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('api.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            showAlert('Thêm item thành công', 'success');
            this.reset();
            location.reload();
        }
    });
});

// Mở modal option
function openModal(itemId) {
    console.log('Opening modal for item:', itemId);
    document.getElementById('modal_item_id').value = itemId;
    document.getElementById('searchOption').value = '';
    document.getElementById('modal_option_id').value = '';
    document.getElementById('selectedOption').style.display = 'none';
    
    fetch('get_item_options.php?item_id=' + itemId)
        .then(response => response.json())
        .then(data => {
            let html = '<h4>Options Hiện Có</h4>';
            if (data.length === 0) {
                html += '<p style="color: #999;">Chưa có option nào</p>';
            } else {
                data.forEach(option => {
                    html += `
                        <div class="option-item">
                            <div class="option-info">
                                <strong>Option: ${option.option_name || 'ID: ' + option.option_id}</strong>
                                <small>Param: ${option.param}</small>
                            </div>
                            <button type="button" class="btn-delete" onclick="deleteOption(${option.id})">🗑️</button>
                        </div>
                    `;
                });
            }
            document.getElementById('optionsList').innerHTML = html;
        });
    
    document.getElementById('optionModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('optionModal').style.display = 'none';
}

// Thêm option
document.getElementById('addOptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('add_option', '1');
    
    console.log('Sending data:', {
        item_id: document.getElementById('modal_item_id').value,
        option_id: document.getElementById('modal_option_id').value,
        param: document.querySelector('#addOptionForm input[name="param"]').value
    });
    
    fetch('api.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(text => {
        console.log('Response:', text);
        try {
            const data = JSON.parse(text);
            if (data.success) {
                showAlert('Thêm option thành công', 'success');
                const itemId = document.getElementById('modal_item_id').value;
                openModal(itemId);
                document.getElementById('addOptionForm').reset();
                document.getElementById('selectedOption').style.display = 'none';
            } else {
                showAlert(data.message || 'Lỗi thêm option', 'error');
            }
        } catch(e) {
            console.error('JSON Parse Error:', e);
            showAlert('Lỗi server: ' + text, 'error');
        }
    }).catch(err => {
        console.error('Fetch Error:', err);
        showAlert('Lỗi kết nối: ' + err, 'error');
    });
});

// Xóa option
function deleteOption(optionId) {
    if (confirm('Xóa option này?')) {
        fetch('api.php?delete_option=' + optionId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Xóa option thành công', 'success');
                    const itemId = document.getElementById('modal_item_id').value;
                    openModal(itemId);
                }
            });
    }
}

// Mở modal sửa
function openEditModal(id, tab_id, is_new, is_sell, type_sell, cost) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_tab_id').value = tab_id;
    document.getElementById('edit_is_new').value = is_new;
    document.getElementById('edit_is_sell').value = is_sell;
    document.getElementById('edit_type_sell').value = type_sell;
    document.getElementById('edit_cost').value = cost;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Sửa item
document.getElementById('editItemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('edit_item', '1');
    
    fetch('api.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            showAlert('Cập nhật item thành công', 'success');
            closeEditModal();
            location.reload();
        }
    });
});

// Xóa item
function deleteItem(id) {
    if (confirm('Xóa item này?')) {
        fetch('api.php?delete=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Xóa item thành công', 'success');
                    document.querySelector(`tr[data-id="${id}"]`).remove();
                }
            });
    }
}

// Tìm kiếm option template
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOption');
    const suggestionsDiv = document.getElementById('optionSuggestions');
    
    function loadAllOptions(query = '') {
        fetch('search_options.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    suggestionsDiv.innerHTML = '<div style="padding: 10px; color: #999;">Không tìm thấy option</div>';
                } else {
                    let html = '';
                    data.forEach(option => {
                        html += `
                            <div class="option-suggestion" onclick="selectOption(${option.id}, '${option.NAME.replace(/'/g, "\\'")}')">
                                <strong>${option.NAME}</strong>
                                <small>ID: ${option.id}</small>
                            </div>
                        `;
                    });
                    suggestionsDiv.innerHTML = html;
                }
                suggestionsDiv.style.display = 'block';
            });
    }
    
    searchInput.addEventListener('focus', function() {
        loadAllOptions('');
    });
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        loadAllOptions(query);
    });
    
    // Tìm kiếm item trong bảng
    const tableSearchInput = document.getElementById('searchInput');
    tableSearchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('.item-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const tempId = row.getAttribute('data-temp-id');
            const tabId = row.getAttribute('data-tab-id');
            
            if (name.includes(query) || tempId.includes(query) || tabId.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

function selectOption(id, name) {
    document.getElementById('modal_option_id').value = id;
    document.getElementById('searchOption').value = name;
    document.getElementById('selectedOption').innerHTML = `✓ Đã chọn: <strong>${name}</strong> (ID: ${id})`;
    document.getElementById('selectedOption').style.display = 'block';
    document.getElementById('optionSuggestions').style.display = 'none';
}

window.onclick = function(event) {
    const optionModal = document.getElementById('optionModal');
    const editModal = document.getElementById('editModal');
    if (event.target === optionModal) {
        closeModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
}
</script>

</body>
</html>