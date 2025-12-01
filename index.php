<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โสภา คาเฟ่ - หน้าหลัก</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <!-- Hero Slideshow Section -->
    <section class="hero-section">
        <div class="slideshow-container">
            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=1200&h=600&fit=crop" alt="Cappuccino">
                <div class="text">คาปูชิโน่เย็น ☕</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=1200&h=600&fit=crop" alt="Espresso">
                <div class="text">เอสเพรสโซ่เข้มข้น ☕</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=1200&h=600&fit=crop" alt="Latte">
                <div class="text">ลาเต้นุ่มนวล ☕</div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <!-- The dots/circles -->
        <div class="dots-container">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="section-title">ยินดีต้อนรับสู่ โสภา คาเฟ่</h2>
                <p class="section-subtitle">เครื่องดื่มคุณภาพ บรรยากาศดี ราคาเป็นกันเอง</p>
                <div class="welcome-features">
                    <div class="feature-item">
                        <div class="feature-icon">☕</div>
                        <h4>กาแฟคุณภาพ</h4>
                        <p>เมล็ดกาแฟคัดสรรจากสวนกาแฟชั้นนำ</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🏠</div>
                        <h4>บรรยากาศสบาย</h4>
                        <p>สถานที่ผ่อนคลาย เหมาะสำหรับทำงานและพักผ่อน</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">💝</div>
                        <h4>บริการเป็นกันเอง</h4>
                        <p>พนักงานยิ้มแย้ม ให้บริการด้วยใจ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Menu Section -->
    <section class="featured-menu">
        <div class="container">
            <h2 class="section-title">เมนูแนะนำ</h2>
            <p class="section-subtitle">ลองชิมเมนูยอดนิยมของเรา</p>

            <div class="menu-grid">
                <!-- Card 1: Cappuccino -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400&h=300&fit=crop" alt="Cappuccino">
                        <div class="menu-badge">ยอดนิยม</div>
                    </div>
                    <div class="menu-content">
                        <h3>☕ คาปูชิโน่</h3>
                        <p>กาแฟเอสเพรสโซ่ผสมนมสด เสิร์ฟร้อนหรือเย็น</p>
                        <div class="menu-price">
                            <span class="price">50 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Espresso -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400&h=300&fit=crop" alt="Espresso">
                    </div>
                    <div class="menu-content">
                        <h3>☕ เอสเพรสโซ่</h3>
                        <p>กาแฟเข้มข้น รสชาติเต็มรูปแบบ</p>
                        <div class="menu-price">
                            <span class="price">45 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Latte -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop" alt="Latte">
                        <div class="menu-badge">แนะนำ</div>
                    </div>
                    <div class="menu-content">
                        <h3>☕ ลาเต้</h3>
                        <p>กาแฟผสมนมสดนุ่มๆ รสชาติกลมกล่อม</p>
                        <div class="menu-price">
                            <span class="price">55 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Americano -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&h=300&fit=crop" alt="Americano">
                    </div>
                    <div class="menu-content">
                        <h3>☕ อเมริกาโน่</h3>
                        <p>เอสเพรสโซ่ผสมน้ำร้อน รสชาติกลมกล่อม</p>
                        <div class="menu-price">
                            <span class="price">40 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Mocha -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1578374173703-4e8e04c7a6ca?w=400&h=300&fit=crop" alt="Mocha">
                    </div>
                    <div class="menu-content">
                        <h3>☕ มอคค่า</h3>
                        <p>กาแฟผสมช็อคโกแลต หอมหวานน่าดื่ม</p>
                        <div class="menu-price">
                            <span class="price">60 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Thai Tea -->
                <div class="menu-card">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop" alt="Thai Tea">
                        <div class="menu-badge">ใหม่</div>
                    </div>
                    <div class="menu-content">
                        <h3>🍵 ชาไทย</h3>
                        <p>ชาไทยต้นตำรับ หอมหวานมันถั่ว</p>
                        <div class="menu-price">
                            <span class="price">45 บาท</span>
                            <button class="btn-order">สั่งเลย</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <a href="menu.php" class="btn-primary">ดูเมนูทั้งหมด</a>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <script src="script.js"></script>
    <script>
        // Slideshow functionality
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
        }

        // Auto slideshow
        setInterval(function() {
            plusSlides(1);
        }, 5000);
    </script>
</body>
</html>