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
                <div class="list" id="dropdownPanel">
                    <div class="dropdown-item" data-category="all">
                        <img src="pictures/list.png" alt="ทั้งหมด">
                        <span>ทั้งหมด</span>
                    </div>
                    <div class="dropdown-item" data-category="กาแฟ">
                        <img src="pictures/list.png" alt="กาแฟ">
                        <span>กาแฟ</span>
                    </div>
                    <div class="dropdown-item" data-category="ชา">
                        <img src="pictures/list.png" alt="ชา">
                        <span>ชา</span>
                    </div>
                    <div class="dropdown-item" data-category="เครื่องดื่มอื่นๆ">
                        <img src="pictures/list.png" alt="เครื่องดื่มอื่นๆ">
                        <span>เครื่องดื่มอื่นๆ</span>
                    </div>
                    <div class="dropdown-item" data-category="ของหวาน">
                        <img src="pictures/list.png" alt="ของหวาน">
                        <span>ของหวาน</span>
                    </div>
                    <div class="dropdown-item" data-category="อาหารว่าง">
                        <img src="pictures/list.png" alt="อาหารว่าง">
                        <span>อาหารว่าง</span>
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
                        <button class="btn-soldout">ซื้อ</button>
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
                        <button class="btn-soldout">ซื้อ</button>
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
                        <button class="btn-soldout">ซื้อ</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('footer.php'); ?>

   
    <script src="script.js"></script>
</body>
</html>