<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    session_start(); // ย้ายมาไว้ก่อน
    include('header.php'); 
    ?>

    <div class="page-background">

        <!-- Menu Header with Dropdown (ขวามือ) -->
        <div class="menu-header">
            <div class="dropdown-category-wrapper">
                <div class="dropdown-category" id="dropdownBtn">
                    ทั้งหมด
                </div>
                <div class="dropdown-panel" id="dropdownPanel">
                    <div class="dropdown-item">
                        <img src="/mnt/data/list.png" alt="Icon 1">
                        <span>Category 1</span>
                    </div>
                    <div class="dropdown-item">
                        <img src="/mnt/data/list.png" alt="Icon 2">
                        <span>Category 2</span>
                    </div>
                    <div class="dropdown-item">
                        <img src="/mnt/data/list.png" alt="Icon 3">
                        <span>Category 3</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Panel -->
        <div class="menu-panel">

            <!-- Menu Card ตัวอย่าง 1 -->
            <div class="menu-card">
                <div class="menu-pic" style="background-image: url('/mnt/data/cf6fa16d-fde7-4ace-a367-c8d93927d02e.png');"></div>
                <div class="menu-info">
                    <div>
                        <h5>MenuName 1</h5>
                        <p>Description 1</p>
                    </div>
                    <div class="price-status">
                        <span>Price</span>
                        <span>Status</span>
                    </div>
                </div>
            </div>

            <!-- Menu Card ตัวอย่าง 2 -->
            <div class="menu-card">
                <div class="menu-pic" style="background-image: url('/mnt/data/cf6fa16d-fde7-4ace-a367-c8d93927d02e.png');"></div>
                <div class="menu-info">
                    <div>
                        <h5>MenuName 2</h5>
                        <p>Description 2</p>
                    </div>
                    <div class="price-status">
                        <span>Price</span>
                        <span>Status</span>
                    </div>
                </div>
            </div>

            <!-- Menu Card ตัวอย่าง 3 -->
            <div class="menu-card">
                <div class="menu-pic" style="background-image: url('/mnt/data/cf6fa16d-fde7-4ace-a367-c8d93927d02e.png');"></div>
                <div class="menu-info">
                    <div>
                        <h5>MenuName 3</h5>
                        <p>Description 3</p>
                    </div>
                    <div class="price-status">
                        <span>Price</span>
                        <span>Status</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- Script สำหรับเปิด/ปิด Dropdown -->
    <script>
        const dropdownBtn = document.getElementById('dropdownBtn');
        const dropdownPanel = document.getElementById('dropdownPanel');

        dropdownBtn.addEventListener('click', () => {
            dropdownPanel.style.display = dropdownPanel.style.display === 'flex' ? 'none' : 'flex';
        });

        // ปิด dropdown เมื่อคลิกข้างนอก
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-category-wrapper')) {
                dropdownPanel.style.display = 'none';
            }
        });
    </script>
</body>
</html>