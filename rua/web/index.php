<?php
require_once "config.php";

// Danh sách tất cả các trang quản lý với thông tin chi tiết
$pages = [
    // Tài khoản & Người chơi
    'account' => ['title' => 'Tài khoản', 'icon' => '👤', 'desc' => 'Quản lý tài khoản người dùng', 'category' => 'user'],
    'player' => ['title' => 'Người chơi', 'icon' => '🎯', 'desc' => 'Thông tin nhân vật trong game', 'category' => 'user'],
    'clan' => ['title' => 'Bang hội', 'icon' => '⚔️', 'desc' => 'Quản lý hệ thống bang hội', 'category' => 'user'],
    
    // Vật phẩm & Template
    'item_template' => ['title' => 'Template vật phẩm', 'icon' => '📦', 'desc' => 'Mẫu cấu hình vật phẩm', 'category' => 'item'],
    'item_options' => ['title' => 'Thuộc tính vật phẩm', 'icon' => '⚙️', 'desc' => 'Cấu hình thuộc tính vật phẩm', 'category' => 'item'],
    'item_option_template' => ['title' => 'Template thuộc tính', 'icon' => '📋', 'desc' => 'Mẫu thuộc tính vật phẩm', 'category' => 'item'],
    'bg_item' => ['title' => 'Background Items', 'icon' => '🎨', 'desc' => 'Vật phẩm trang trí nền', 'category' => 'item'],
    
    // Cửa hàng & Giao dịch
    'shop' => ['title' => 'Cửa hàng', 'icon' => '🏬', 'desc' => 'Quản lý hệ thống cửa hàng', 'category' => 'shop'],
    'item_shop' => ['title' => 'Shop vật phẩm', 'icon' => '🛍️', 'desc' => 'Vật phẩm bán trong cửa hàng', 'category' => 'shop'],
    'item_shop_option' => ['title' => 'Tùy chọn shop', 'icon' => '💰', 'desc' => 'Cấu hình giá cả và điều kiện', 'category' => 'shop'],
    'shop_ky_gui' => ['title' => 'Shop ký gửi', 'icon' => '🏪', 'desc' => 'Hệ thống ký gửi vật phẩm', 'category' => 'shop'],
    'tab_shop' => ['title' => 'Tab cửa hàng', 'icon' => '📑', 'desc' => 'Phân loại tab trong shop', 'category' => 'shop'],
    'giftcode' => ['title' => 'Gift Code', 'icon' => '🎁', 'desc' => 'Tạo và quản lý mã quà tặng', 'category' => 'shop'],
    
    // Game Content & Templates
    'skill_template' => ['title' => 'Template kỹ năng', 'icon' => '⚔️', 'desc' => 'Mẫu cấu hình kỹ năng', 'category' => 'game'],
    'intrinsic' => ['title' => 'Nội tại', 'icon' => '✨', 'desc' => 'Kỹ năng nội tại nhân vật', 'category' => 'game'],
    'map_template' => ['title' => 'Template bản đồ', 'icon' => '🗺️', 'desc' => 'Thiết kế các khu vực game', 'category' => 'game'],
    'mob_template' => ['title' => 'Template quái vật', 'icon' => '👹', 'desc' => 'Cấu hình thông số quái vật', 'category' => 'game'],
    'npc_template' => ['title' => 'Template NPC', 'icon' => '🤖', 'desc' => 'Cấu hình nhân vật phi người chơi', 'category' => 'game'],
    
    // Nhiệm vụ & Sự kiện
    'task_main_template' => ['title' => 'Nhiệm vụ chính', 'icon' => '📋', 'desc' => 'Template nhiệm vụ cốt truyện', 'category' => 'quest'],
    'task_sub_template' => ['title' => 'Nhiệm vụ phụ', 'icon' => '📝', 'desc' => 'Template nhiệm vụ bổ sung', 'category' => 'quest'],
    'side_task_template' => ['title' => 'Nhiệm vụ hàng ngày', 'icon' => '🔄', 'desc' => 'Cấu hình nhiệm vụ lặp lại', 'category' => 'quest'],
    'event' => ['title' => 'Sự kiện', 'icon' => '🎉', 'desc' => 'Quản lý sự kiện đặc biệt', 'category' => 'quest'],
    'achievement' => ['title' => 'Thành tựu', 'icon' => '🏆', 'desc' => 'Hệ thống thành tích và phần thưởng', 'category' => 'quest'],
    
    // Giao diện & Tùy chỉnh
    'head_avatar' => ['title' => 'Avatar đầu', 'icon' => '👤', 'desc' => 'Hình ảnh đại diện nhân vật', 'category' => 'ui'],
    'array_head' => ['title' => 'Mảng đầu', 'icon' => '🎭', 'desc' => 'Cấu hình kiểu đầu nhân vật', 'category' => 'ui'],
    'part' => ['title' => 'Phần thân thể', 'icon' => '🧩', 'desc' => 'Quản lý bộ phận nhân vật', 'category' => 'ui'],
    'notify' => ['title' => 'Thông báo', 'icon' => '📢', 'desc' => 'Gửi thông báo đến người chơi', 'category' => 'ui']
];

// Phân loại theo category
$categories = [
    'user' => ['name' => '👥 Tài khoản & Người chơi', 'color' => '#4CAF50'],
    'item' => ['name' => '📦 Vật phẩm & Template', 'color' => '#2196F3'],
    'shop' => ['name' => '🛒 Cửa hàng & Giao dịch', 'color' => '#FF9800'],
    'game' => ['name' => '🎮 Nội dung Game', 'color' => '#9C27B0'],
    'quest' => ['name' => '📋 Nhiệm vụ & Sự kiện', 'color' => '#F44336'],
    'ui' => ['name' => '🎨 Giao diện & Tùy chỉnh', 'color' => '#00BCD4']
];

// Thống kê tổng quan
$stats = [];
$main_tables = ['account', 'player', 'item_template', 'skill_template', 'event', 'achievement', 'clan', 'mob_template'];
foreach($main_tables as $table) {
    try {
        $count_query = $conn->query("SELECT COUNT(*) as count FROM $table");
        if($count_query) {
            $stats[$table] = $count_query->fetch_assoc()['count'];
        } else {
            $stats[$table] = 0;
        }
    } catch(Exception $e) {
        $stats[$table] = 0;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🎮 Game Management System - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .header p {
            color: #666;
            font-size: 1.1em;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--color, #667eea) 0%, var(--color-end, #764ba2) 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            transform: perspective(1000px) rotateX(0deg);
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .stat-card:hover {
            transform: perspective(1000px) rotateX(10deg) translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .search-box {
            flex: 1;
            min-width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1.1em;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            background: white;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9em;
            text-decoration: none;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .category-section {
            margin-bottom: 50px;
        }
        
        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px 0;
            border-bottom: 2px solid;
        }
        
        .category-header h2 {
            font-size: 1.5em;
            margin: 0;
        }
        
        .category-count {
            margin-left: auto;
            background: rgba(255,255,255,0.9);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .pages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .page-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e0e0e0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            display: block;
        }
        
        .page-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .page-card:hover::before {
            left: 100%;
        }
        
        .page-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }
        
        .page-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            display: block;
        }
        
        .page-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .page-desc {
            color: #666;
            font-size: 0.9em;
            line-height: 1.4;
        }
        
        .page-card.hidden {
            display: none !important;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #667eea;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.5em;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quick-stat {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .quick-stat-number {
            font-size: 1.8em;
            font-weight: bold;
            display: block;
        }
        
        .quick-stat-label {
            font-size: 0.8em;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                min-width: auto;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .pages-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="top">
        <div class="header">
            <h1>🎮 Game Management System</h1>
            <p>Hệ thống quản lý game toàn diện - Quản lý <?= count($pages) ?> modules một cách dễ dàng</p>
        </div>
        
        <!-- Thống kê nhanh -->
        <div class="quick-stats">
            <div class="quick-stat">
                <span class="quick-stat-number"><?= count($pages) ?></span>
                <div class="quick-stat-label">Modules</div>
            </div>
            <div class="quick-stat">
                <span class="quick-stat-number"><?= count($categories) ?></span>
                <div class="quick-stat-label">Danh mục</div>
            </div>
            <div class="quick-stat">
                <span class="quick-stat-number"><?= array_sum($stats) ?></span>
                <div class="quick-stat-label">Tổng bản ghi</div>
            </div>
        </div>
        
        <!-- Thống kê chi tiết -->
        <div class="stats-overview">
            <div class="stat-card" style="--color: #4CAF50; --color-end: #45a049;">
                <span class="stat-number"><?= $stats['account'] ?></span>
                <div class="stat-label">Tài khoản</div>
            </div>
            <div class="stat-card" style="--color: #2196F3; --color-end: #1976D2;">
                <span class="stat-number"><?= $stats['player'] ?></span>
                <div class="stat-label">Người chơi</div>
            </div>
            <div class="stat-card" style="--color: #FF9800; --color-end: #F57C00;">
                <span class="stat-number"><?= $stats['item_template'] ?></span>
                <div class="stat-label">Vật phẩm</div>
            </div>
            <div class="stat-card" style="--color: #9C27B0; --color-end: #7B1FA2;">
                <span class="stat-number"><?= $stats['skill_template'] ?></span>
                <div class="stat-label">Kỹ năng</div>
            </div>
        </div>
        
        <!-- Điều khiển -->
        <div class="controls">
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="🔍 Tìm kiếm module quản lý...">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">Tất cả</button>
                <?php foreach($categories as $key => $cat): ?>
                <button class="filter-btn" data-category="<?= $key ?>"><?= explode(' ', $cat['name'])[1] ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Danh sách các trang theo danh mục -->
        <div id="categoriesContainer">
            <?php foreach($categories as $catKey => $category): ?>
            <?php 
            $categoryPages = array_filter($pages, function($page) use ($catKey) {
                return $page['category'] === $catKey;
            });
            ?>
            <div class="category-section" data-category="<?= $catKey ?>">
                <div class="category-header" style="border-color: <?= $category['color'] ?>; color: <?= $category['color'] ?>;">
                    <h2><?= $category['name'] ?></h2>
                    <span class="category-count"><?= count($categoryPages) ?> modules</span>
                </div>
                <div class="pages-grid">
                    <?php foreach($categoryPages as $file => $info): ?>
                    <a href="<?= $file ?>.php" class="page-card" 
                       data-title="<?= strtolower($info['title']) ?>" 
                       data-desc="<?= strtolower($info['desc']) ?>"
                       data-category="<?= $info['category'] ?>">
                        <span class="page-icon"><?= $info['icon'] ?></span>
                        <div class="page-title"><?= $info['title'] ?></div>
                        <div class="page-desc"><?= $info['desc'] ?></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <a href="#top" class="back-to-top">↑</a>
    
    <script>
        // Tìm kiếm real-time
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.page-card');
            const sections = document.querySelectorAll('.category-section');
            
            if (searchTerm === '') {
                // Hiện tất cả
                cards.forEach(card => card.classList.remove('hidden'));
                sections.forEach(section => section.style.display = 'block');
            } else {
                // Tìm kiếm
                sections.forEach(section => {
                    const sectionCards = section.querySelectorAll('.page-card');
                    let hasVisibleCards = false;
                    
                    sectionCards.forEach(card => {
                        const title = card.getAttribute('data-title');
                        const desc = card.getAttribute('data-desc');
                        
                        if (title.includes(searchTerm) || desc.includes(searchTerm)) {
                            card.classList.remove('hidden');
                            hasVisibleCards = true;
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    
                    section.style.display = hasVisibleCards ? 'block' : 'none';
                });
            }
        });
        
        // Bộ lọc theo danh mục
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                const sections = document.querySelectorAll('.category-section');
                const cards = document.querySelectorAll('.page-card');
                
                // Cập nhật active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Lọc sections
                if (category === 'all') {
                    sections.forEach(section => section.style.display = 'block');
                    cards.forEach(card => card.classList.remove('hidden'));
                } else {
                    sections.forEach(section => {
                        if (section.getAttribute('data-category') === category) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                }
                
                // Reset search
                document.getElementById('searchInput').value = '';
            });
        });
        
        // Smooth scroll cho back to top
        document.querySelector('.back-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Animation khi load
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('.page-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
        
        // Hiển thị/ẩn back to top button
        window.addEventListener('scroll', function() {
            const backToTop = document.querySelector('.back-to-top');
            if (window.scrollY > 300) {
                backToTop.style.opacity = '1';
                backToTop.style.transform = 'translateY(0)';
            } else {
                backToTop.style.opacity = '0';
                backToTop.style.transform = 'translateY(20px)';
            }
        });
    </script>
</body>
</html>
