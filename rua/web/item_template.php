<?php
require_once "config.php";
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $action = $_POST['action'] ?? '';

    // === Thêm mới ===
    if ($action === "add") {
        $stmt = $conn->prepare("INSERT INTO item_template 
            (id, TYPE, gender, NAME, description, level, icon_id, part, is_up_to_up, power_require, gold, gem, head, body, leg) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iiissiiiiiiiiii",
            $_POST['id'], $_POST['TYPE'], $_POST['gender'], $_POST['NAME'], $_POST['description'],
            $_POST['level'], $_POST['icon_id'], $_POST['part'], $_POST['is_up_to_up'], $_POST['power_require'],
            $_POST['gold'], $_POST['gem'], $_POST['head'], $_POST['body'], $_POST['leg']
        );
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Thêm thành công"]);
        exit;
    }

    // === Cập nhật ===
    if ($action === "update") {
        $stmt = $conn->prepare("UPDATE item_template SET 
            TYPE=?, gender=?, NAME=?, description=?, level=?, icon_id=?, part=?, is_up_to_up=?, power_require=?, gold=?, gem=?, head=?, body=?, leg=? 
            WHERE id=?");
        $stmt->bind_param(
            "iissiiiiiiiiiii",
            $_POST['TYPE'], $_POST['gender'], $_POST['NAME'], $_POST['description'],
            $_POST['level'], $_POST['icon_id'], $_POST['part'], $_POST['is_up_to_up'], $_POST['power_require'],
            $_POST['gold'], $_POST['gem'], $_POST['head'], $_POST['body'], $_POST['leg'], $_POST['id']
        );
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Cập nhật thành công"]);
        exit;
    }

    // === Xóa ===
    if ($action === "delete") {
        $stmt = $conn->prepare("DELETE FROM item_template WHERE id=?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Xóa thành công"]);
        exit;
    }

    // === Danh sách (phân trang) với lọc ===
    if ($action === "list") {
        $limit = 500;
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $search = $_POST['search'] ?? "";
        $filterType = $_POST['filterType'] ?? "";
        $filterGender = $_POST['filterGender'] ?? "";
        $filterLevel = $_POST['filterLevel'] ?? "";
        $filterPart = $_POST['filterPart'] ?? "";

        $where = "1=1";
        $params = [];
        $types = "";

        if ($search !== "") {
            $where .= " AND (id LIKE ? OR NAME LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $types .= "ss";
        }

        if ($filterType !== "") {
            $where .= " AND TYPE = ?";
            $params[] = (int)$filterType;
            $types .= "i";
        }

        if ($filterGender !== "") {
            $where .= " AND gender = ?";
            $params[] = (int)$filterGender;
            $types .= "i";
        }

        if ($filterLevel !== "") {
            $where .= " AND level = ?";
            $params[] = (int)$filterLevel;
            $types .= "i";
        }

        if ($filterPart !== "") {
            $where .= " AND part = ?";
            $params[] = (int)$filterPart;
            $types .= "i";
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM item_template WHERE $where ORDER BY id ASC LIMIT ?, ?";
        $stmt = $conn->prepare($query);
        
        $params[] = $offset;
        $params[] = $limit;
        $types .= "ii";
        
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;

        $totalRows = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
        $totalPages = ceil($totalRows / $limit);

        echo json_encode([
            "status" => "success",
            "data" => $rows,
            "page" => $page,
            "totalPages" => $totalPages
        ]);
        exit;
    }

    // === Lấy danh sách giá trị duy nhất cho dropdown ===
    if ($action === "getFilters") {
        $types = $conn->query("SELECT DISTINCT TYPE FROM item_template ORDER BY TYPE")->fetch_all(MYSQLI_ASSOC);
        $genders = $conn->query("SELECT DISTINCT gender FROM item_template ORDER BY gender")->fetch_all(MYSQLI_ASSOC);
        $levels = $conn->query("SELECT DISTINCT level FROM item_template ORDER BY level")->fetch_all(MYSQLI_ASSOC);
        $parts = $conn->query("SELECT DISTINCT part FROM item_template ORDER BY part")->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode([
            "types" => $types,
            "genders" => $genders,
            "levels" => $levels,
            "parts" => $parts
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý Item Template</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { 
  font-family: "Segoe UI", Arial, sans-serif; 
  background: #f9f9f9; 
  margin: 20px; 
  font-size: 16px;
  color: #222;
}

h1 { 
  text-align: center; 
  font-size: 28px; 
  color: #333; 
  margin-bottom: 25px;
}

h2 {
  font-size: 22px;
  color: #444;
  margin-top: 25px;
}

form { 
  background: #fff; 
  padding: 15px; 
  margin-bottom: 20px; 
  border-radius: 8px; 
  box-shadow: 0 0 4px rgba(0,0,0,0.1); 
}

input[type="text"], input[type="number"], select {
  font-size: 16px;
  padding: 6px 10px;
  margin: 4px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 130px;
}

select {
  width: 140px;
}

button { 
  cursor: pointer; 
  background: #007BFF; 
  border: none; 
  color: #fff; 
  padding: 8px 16px; 
  border-radius: 5px; 
  font-size: 16px;
  transition: all 0.2s ease;
}
button:hover { background: #0056b3; }

.btnReset {
  background: #6c757d;
  margin-left: 10px;
}
.btnReset:hover {
  background: #5a6268;
}

/* === Bảng hiển thị === */
.table-container {
  max-height: 600px;
  overflow-y: auto;
  border: 1px solid #ccc;
  background: #fff;
  width: 100%;
  border-radius: 8px;
}

table {
  border-collapse: collapse;
  width: 100%;
  table-layout: fixed;    
  word-wrap: break-word;
  white-space: normal;
  font-size: 15px;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: center;
  vertical-align: top;
  word-break: break-word;
  white-space: normal;
}

th {
  background: #f0f0f0;
  position: sticky;
  top: 0;
  z-index: 2;
  font-size: 16px;
}

td input {
  width: 100%;
  height: auto;
  font-size: 15px;
  white-space: normal;
  word-break: break-word;
}

th:nth-child(1), td:nth-child(1) { width: 60px; }   
th:nth-child(4), td:nth-child(4) { width: 200px; }  
th:nth-child(5), td:nth-child(5) { width: 350px; }  
th:last-child, td:last-child { width: 150px; }      

.pagination { 
  text-align: center; 
  margin-top: 12px; 
}

.pagination button { 
  margin: 3px; 
  padding: 6px 12px;
}

/* === Form tìm kiếm và lọc === */
#searchForm {
  text-align: center;
  background: #fff;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 25px;
}

#searchForm input {
  font-size: 18px;
  padding: 8px 14px;
  width: 300px;
  border-radius: 6px;
  border: 1px solid #aaa;
}

#searchForm button {
  font-size: 18px;
  padding: 8px 16px;
  background: #28a745;
}
#searchForm button:hover {
  background: #1e7e34;
}

/* === Form lọc === */
#filterForm {
  background: #f0f8ff;
  padding: 15px;
  border-left: 4px solid #007BFF;
  margin-bottom: 20px;
}

#filterForm label {
  display: inline-block;
  margin-right: 15px;
  font-weight: 500;
}

#filterForm select {
  margin-right: 10px;
}

.filter-group {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  align-items: center;
}

.filter-item {
  display: flex;
  align-items: center;
  gap: 5px;
}
</style>
</head>
<body>

<h1>📜 Quản lý Item Template</h1>

<form id="searchForm">
    🔍 Tìm kiếm ID hoặc Name: 
    <input type="text" name="search" id="searchInput" placeholder="Nhập ID hoặc tên item...">
    <button type="submit">Tìm</button>
</form>

<div id="filterForm">
    <h3 style="margin-top: 0;">🔎 Bộ lọc nâng cao</h3>
    <div class="filter-group">
        <div class="filter-item">
            <label>TYPE:</label>
            <select id="filterType">
                <option value="">-- Tất cả --</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Gender:</label>
            <select id="filterGender">
                <option value="">-- Tất cả --</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Level:</label>
            <select id="filterLevel">
                <option value="">-- Tất cả --</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Part:</label>
            <select id="filterPart">
                <option value="">-- Tất cả --</option>
            </select>
        </div>
        <button type="button" id="applyFilterBtn" style="background: #28a745;">Áp dụng</button>
        <button type="button" class="btnReset" id="resetFilterBtn">Đặt lại</button>
    </div>
</div>

<h2>➕ Thêm mới</h2>
<form id="addForm">
    ID: <input type="number" name="id" required>
    TYPE: <input type="number" name="TYPE" value="0">
    Gender: <input type="number" name="gender" value="0">
    Name: <input type="text" name="NAME" required>
    Description: <input type="text" name="description">
    Level: <input type="number" name="level" value="0">
    Icon ID: <input type="number" name="icon_id" value="0">
    Part: <input type="number" name="part" value="0">
    IsUp: <input type="number" name="is_up_to_up" value="0">
    PowerReq: <input type="number" name="power_require" value="0">
    Gold: <input type="number" name="gold" value="0">
    Gem: <input type="number" name="gem" value="0">
    Head: <input type="number" name="head" value="-1">
    Body: <input type="number" name="body" value="-1">
    Leg: <input type="number" name="leg" value="-1">
    <button type="submit">Thêm</button>
</form>

<h2>📋 Danh sách Item Template</h2>
<div class="table-container">
<table id="dataTable">
    <thead>
        <tr>
            <th>ID</th><th>TYPE</th><th>Gender</th><th>Name</th><th>Description</th>
            <th>Level</th><th>Icon</th><th>Part</th><th>IsUp</th><th>PowerReq</th>
            <th>Gold</th><th>Gem</th><th>Head</th><th>Body</th><th>Leg</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
</div>

<div class="pagination" id="pagination"></div>

<script>
let currentPage = 1;
let currentSearch = "";
let currentFilters = {};

function loadFilters() {
    $.post("item_template.php", {action:"getFilters"}, function(res){
        // Load TYPE
        $("#filterType").append(res.types.map(t => `<option value="${t.TYPE}">${t.TYPE}</option>`).join(""));
        // Load Gender
        $("#filterGender").append(res.genders.map(g => `<option value="${g.gender}">${g.gender}</option>`).join(""));
        // Load Level
        $("#filterLevel").append(res.levels.map(l => `<option value="${l.level}">${l.level}</option>`).join(""));
        // Load Part
        $("#filterPart").append(res.parts.map(p => `<option value="${p.part}">${p.part}</option>`).join(""));
    },"json");
}

function loadData(page=1, search="", filters={}) {
    let data = {action:"list", page:page, search:search};
    Object.assign(data, filters);
    
    $.post("item_template.php", data, function(res){
        if(res.status=="success") {
            currentPage = res.page;
            currentSearch = search;
            currentFilters = filters;
            let tbody = "";
            if (res.data.length === 0) {
                tbody = `<tr><td colspan="16">Không có dữ liệu</td></tr>`;
            } else {
                res.data.forEach(row=>{
                    tbody += `
                    <tr>
                      <td><input type="number" name="id" value="${row.id}" readonly></td>
                      <td><input type="number" name="TYPE" value="${row.TYPE}"></td>
                      <td><input type="number" name="gender" value="${row.gender}"></td>
                      <td><input type="text" name="NAME" value="${row.NAME}"></td>
                      <td><textarea name="description" style="width:100%;font-size:15px;">${row.description}</textarea></td>
                      <td><input type="number" name="level" value="${row.level}"></td>
                      <td><input type="number" name="icon_id" value="${row.icon_id}"></td>
                      <td><input type="number" name="part" value="${row.part}"></td>
                      <td><input type="number" name="is_up_to_up" value="${row.is_up_to_up}"></td>
                      <td><input type="number" name="power_require" value="${row.power_require}"></td>
                      <td><input type="number" name="gold" value="${row.gold}"></td>
                      <td><input type="number" name="gem" value="${row.gem}"></td>
                      <td><input type="number" name="head" value="${row.head}"></td>
                      <td><input type="number" name="body" value="${row.body}"></td>
                      <td><input type="number" name="leg" value="${row.leg}"></td>
                      <td>
                        <button class="updateBtn">Cập nhật</button>
                        <button class="deleteBtn">Xóa</button>
                      </td>
                    </tr>`;
                });
            }
            $("#dataTable tbody").html(tbody);
            renderPagination(res.page, res.totalPages);
        }
    },"json");
}

function renderPagination(page, totalPages) {
    let html = "";
    if (totalPages <= 1) return $("#pagination").html("");
    for (let i=1; i<=totalPages; i++) {
        html += `<button class="pageBtn" data-page="${i}" ${i===page?'style="background:#007BFF;color:white"':''}>${i}</button>`;
    }
    $("#pagination").html(html);
}

$("#addForm").submit(function(e){
    e.preventDefault();
    $.post("item_template.php", $(this).serialize()+"&action=add", function(res){
        alert(res.message);
        loadData(currentPage, currentSearch, currentFilters);
        $("#addForm")[0].reset();
    },"json");
});

$(document).on("click",".updateBtn",function(){
    let row=$(this).closest("tr");
    let data={action:"update"};
    row.find("input, textarea").each(function(){ data[$(this).attr("name")]=$(this).val(); });
    $.post("item_template.php",data,function(res){ alert(res.message); },"json");
});

$(document).on("click",".deleteBtn",function(){
    if(!confirm("Xóa item này?")) return;
    let id=$(this).closest("tr").find("input[name='id']").val();
    $.post("item_template.php",{action:"delete",id:id},function(res){
        alert(res.message);
        loadData(currentPage, currentSearch, currentFilters);
    },"json");
});

$(document).on("click",".pageBtn",function(){
    let p = parseInt($(this).attr("data-page"));
    loadData(p, currentSearch, currentFilters);
});

$("#searchForm").submit(function(e){
    e.preventDefault();
    loadData(1, $("#searchInput").val(), currentFilters);
});

$("#applyFilterBtn").click(function(){
    let filters = {
        filterType: $("#filterType").val(),
        filterGender: $("#filterGender").val(),
        filterLevel: $("#filterLevel").val(),
        filterPart: $("#filterPart").val()
    };
    loadData(1, currentSearch, filters);
});

$("#resetFilterBtn").click(function(){
    $("#filterType").val("");
    $("#filterGender").val("");
    $("#filterLevel").val("");
    $("#filterPart").val("");
    loadData(1, currentSearch, {});
});

loadFilters();
loadData();
</script>
</body>
</html>