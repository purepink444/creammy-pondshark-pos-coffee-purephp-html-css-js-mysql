<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>ระบบเพิ่มเมนู</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php include 'header.php'; ?>

  <main class="page" role="main">

    <!-- Left gallery -->
    <aside class="gallery" aria-label="รายการสินค้า">
      <div class="card">เอสเพรสโซ่</div>
      <div class="card">คาปูชิโน่</div>
      <div class="card">ลาเต้</div>
      <div class="card">อเมริกาโน่</div>
      <div class="card">ชาเขียว</div>
      <div class="card">โกโก้</div>
    </aside>

    <!-- Main content panel -->
    <section class="main-panel" aria-label="ตะกร้าสินค้า / รายละเอียด">

      <!-- Header -->
      <div class="panel-header">
        <h2>ตะกร้าสินค้า</h2>
        <button id="add-menu" type="button">+ เพิ่มเมนู</button>
      </div>

      <!-- ตาราง -->
      <table id="cart-table">
        <thead>
          <tr>
            <th>ลำดับ</th>
            <th>ชื่อเมนู</th>
            <th>ราคา</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody id="cart-body"></tbody>
      </table>

      <!-- ยอดรวม -->
      <div id="totals">
        รวมทั้งหมด: 0 บาท | VAT 7%: 0 บาท | ราคาสุทธิ: 0 บาท
      </div>

      <!-- ปุ่มลบทั้งหมด + ยืนยัน -->
      <div class="button-row">
        <button id="clear-all" type="button">ลบทั้งหมด</button>
        <button class="confirm" type="button">ยืนยันการสั่งซื้อ</button>
      </div>

    </section>
  </main>


  <!-- Modal เพิ่มเมนูใหม่ -->
  <div id="modal-add" class="modal">
    <div class="modal-content">
      <h3>เพิ่มเมนูใหม่</h3>

      <label>ชื่อเมนู</label>
      <input type="text" id="new-menu-name" placeholder="เช่น มอคค่าเย็น">

      <label>ราคา</label>
      <input type="number" id="new-menu-price" placeholder="เช่น 55">

      <label>คำอธิบาย</label>
      <input type="text" id="new-menu-desc" placeholder="เช่น กาแฟผสมช็อกโกแลต">

      <label>รูปภาพ</label>
      <input type="file" id="new-menu-image" >

      <div class="modal-buttons">
        <button id="close-modal" class="btn-cancel">ปิด</button>
        <button id="save-menu" class="btn-save">เพิ่มเมนู</button>
      </div>
    </div>
  </div>

  <div id="alert-popup" class="modal-alert">
    <div class="modal-alert-content">
      <img src="pictures/check.png" alt="success">
      <p id="alert-message">เพิ่มเมนูใหม่สำเร็จ</p>
      <button id="close-alert" class="btn-confirm">ตกลง</button>
    </div>
  </div>

  <script src="script.js"></script>

</body>
</html>
