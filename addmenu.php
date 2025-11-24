<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mockup - ระบบจัดการร้านกาแฟ</title>
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
      <h2 style="margin-top:0">ตะกร้าสินค้า</h2>
      <p style="color:var(--muted)">พื้นที่แสดงรายการที่เลือก / รายละเอียดเพิ่มเติม / สรุปรายการและราคา</p>

      <!-- ปุ่มเพิ่มเมนูใหม่ -->
      <button id="add-menu">เพิ่มเมนู</button>

      <!-- ตารางตะกร้า -->
      <table id="cart-table">
        <thead>
          <tr>
            <th>ลำดับ</th>
            <th>ชื่อเมนู</th>
            <th>ราคา</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <!-- รายการจะมาแสดงที่นี่ -->
        </tbody>
      </table>

      <!-- ยอดรวม -->
      <div id="totals">
        รวมทั้งหมด: 0 บาท | VAT 7%: 0 บาท | ราคาสุทธิ: 0 บาท
      </div>

      <!-- ปุ่มยืนยันและลบทั้งหมด -->
      <div class="button-row">
        <button id="clear-all" type="button">ลบทั้งหมด</button>
        <button class="confirm" type="button">ยืนยันการสั่งซื้อ</button>
      </div>
    </section>

  </main>

<script>
const cards = document.querySelectorAll('.gallery .card');
const tableBody = document.querySelector('#cart-table tbody');
const totalsDiv = document.getElementById('totals');
const clearAllBtn = document.getElementById('clear-all');
let cart = [];

// ฟังก์ชันอัปเดตตารางและคำนวณยอด
function updateCart() {
  tableBody.innerHTML = '';
  let total = 0;

  cart.forEach((item, i) => {
    total += item.price;
    tableBody.innerHTML += `<tr>
      <td>${i+1}</td>
      <td>${item.name}</td>
      <td>${item.price} บาท</td>
      <td>
        <button class="btn-delete" data-index="${i}">ลบ</button>
      </td>
    </tr>`;
  });

  const vat = total * 0.07;
  const grandTotal = total + vat;

  totalsDiv.textContent = `รวมทั้งหมด: ${total.toFixed(2)} บาท | VAT 7%: ${vat.toFixed(2)} บาท | ราคาสุทธิ: ${grandTotal.toFixed(2)} บาท`;

  // เพิ่ม event ให้ปุ่มจัดการ (ลบ)
  const deleteButtons = document.querySelectorAll('.btn-delete');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.getAttribute('data-index'));
      cart.splice(index, 1); // ลบรายการจาก cart
      updateCart(); // อัปเดตตารางและยอดรวมใหม่
    });
  });
}

// ฟังก์ชันเพิ่มรายการลงตาราง
function addToCart(name, price) {
  cart.push({ name, price });
  updateCart();
}

// คลิกเมนูเดิม
cards.forEach(card => {
  card.addEventListener('click', () => {
    const name = card.textContent.trim();
    const price = 50; // ตัวอย่างราคา
    addToCart(name, price);
  });
});

// เพิ่มเมนูใหม่แบบ default
document.getElementById('add-menu').addEventListener('click', () => {
  const name = "เมนูใหม่"; // ชื่อเมนูใหม่
  const price = 50;        // ราคา default

  const newCard = document.createElement('div');
  newCard.className = 'card';
  newCard.textContent = name;

  newCard.addEventListener('click', () => {
    addToCart(name, price);
  });

  document.querySelector('.gallery').appendChild(newCard);
});

// ปุ่มลบทั้งหมด
clearAllBtn.addEventListener('click', () => {
  cart = [];
  updateCart();
});
</script>

</body>
</html>
