// ==============================
// ตัวแปรหลัก
// ==============================
const cards = document.querySelectorAll('.gallery .card');
const tableBody = document.querySelector('#cart-body');
const totalsDiv = document.getElementById('totals');
const clearAllBtn = document.getElementById('clear-all');

// Modal Elements
const modalAdd = document.getElementById('modal-add');
const addMenuBtn = document.getElementById('add-menu');
const closeModalBtn = document.getElementById('close-modal');
const saveMenuBtn = document.getElementById('save-menu');

// Alert Popup Elements
const alertPopup = document.getElementById('alert-popup');
const alertMessage = document.getElementById('alert-message');
const closeAlertBtn = document.getElementById('close-alert');

let cart = [];

// ==============================
// Update Cart
// ==============================
function updateCart() {
  tableBody.innerHTML = '';
  let total = 0;

  cart.forEach((item, i) => {
    total += item.price;
    tableBody.innerHTML += `
      <tr>
        <td>${i+1}</td>
        <td>${item.name}</td>
        <td>${item.price} บาท</td>
        <td><button class="btn-delete" data-index="${i}">ลบ</button></td>
      </tr>
    `;
  });

  const vat = total * 0.07;
  const grand = total + vat;

  totalsDiv.textContent =
    `รวมทั้งหมด: ${total.toFixed(2)} บาท | VAT 7%: ${vat.toFixed(2)} บาท | ราคาสุทธิ: ${grand.toFixed(2)} บาท`;

  // Event listener สำหรับปุ่มลบ
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = () => {
      const i = btn.getAttribute('data-index');
      cart.splice(i, 1);
      updateCart();
    };
  });
}

// ==============================
// เพิ่มเมนูเข้าตะกร้า
// ==============================
function addToCart(name, price) {
  cart.push({ name, price });
  updateCart();
}

// คลิกการ์ดเมนูที่มีอยู่แล้ว
cards.forEach(card => {
  card.addEventListener('click', () => {
    addToCart(card.textContent.trim(), 50);
  });
});

// ==============================
// Modal: เปิด/ปิด
// ==============================
addMenuBtn.addEventListener('click', () => {
  modalAdd.classList.add('show');
});

closeModalBtn.addEventListener('click', () => {
  modalAdd.classList.remove('show');
});

// ปิด modal เมื่อคลิกนอก modal-content
modalAdd.addEventListener('click', (e) => {
  if (e.target === modalAdd) {
    modalAdd.classList.remove('show');
  }
});

// ==============================
// บันทึกเมนูใหม่ (ส่งไป PHP)
// ==============================
saveMenuBtn.addEventListener('click', async () => {
  const menuName = document.getElementById('new-menu-name').value.trim();
  const menuPrice = document.getElementById('new-menu-price').value;
  const menuDesc = document.getElementById('new-menu-desc').value.trim();
  const menuImage = document.getElementById('new-menu-image').files[0];

  // ตรวจสอบข้อมูล
  if (!menuName || !menuPrice) {
    alert('กรุณากรอกชื่อเมนูและราคา');
    return;
  }

  // สร้าง FormData สำหรับส่งข้อมูลพร้อมรูปภาพ
  const formData = new FormData();
  formData.append('menuName', menuName);
  formData.append('menuPrice', menuPrice);
  formData.append('menuDesc', menuDesc);
  if (menuImage) {
    formData.append('menuImage', menuImage);
  }

  try {
    // ส่งข้อมูลไปยัง PHP
    const response = await fetch('add_menu.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      // ปิด modal เพิ่มเมนู
      modalAdd.classList.remove('show');
      
      // แสดง alert popup
      alertMessage.textContent = 'เพิ่มเมนูใหม่สำเร็จ';
      alertPopup.style.display = 'flex';
      
      // ล้างฟอร์ม
      document.getElementById('new-menu-name').value = '';
      document.getElementById('new-menu-price').value = '';
      document.getElementById('new-menu-desc').value = '';
      document.getElementById('new-menu-image').value = '';
      
      // รีโหลดหน้าหลังจาก 1.5 วินาที
      setTimeout(() => {
        location.reload();
      }, 1500);
    } else {
      alert('เกิดข้อผิดพลาด: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('เกิดข้อผิดพลาดในการเพิ่มเมนู');
  }
});

// ==============================
// Alert Popup: ปิด
// ==============================
closeAlertBtn.addEventListener('click', () => {
  alertPopup.style.display = 'none';
  location.reload();
});

// ==============================
// ลบทั้งหมด
// ==============================
clearAllBtn.onclick = () => {
  if (confirm('ต้องการลบรายการทั้งหมดใช่หรือไม่?')) {
    cart = [];
    updateCart();
  }
};