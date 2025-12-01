// ==============================
// ตัวแปรหลัก
// ==============================
const cards = document.querySelectorAll('.gallery .card');
const tableBody = document.querySelector('#cart-body');
const subtotalSpan = document.getElementById('subtotal');
const vatSpan = document.getElementById('vat');
const totalSpan = document.getElementById('total');
const clearAllBtn = document.getElementById('clear-all');
const confirmOrderBtn = document.getElementById('confirm-order');

// Modal Elements
const modalAdd = document.getElementById('modal-add');
const addMenuBtn = document.getElementById('add-menu');
const closeModalBtn = document.getElementById('close-modal');
const saveMenuBtn = document.getElementById('save-menu');
const addMenuForm = document.getElementById('add-menu-form');

// Alert Popup Elements
const alertPopup = document.getElementById('alert-popup');
const alertMessage = document.getElementById('alert-message');
const closeAlertBtn = document.getElementById('close-alert');

// ตะกร้าสินค้า
let cart = [];

// ==============================
// ฟังก์ชันอัพเดทตะกร้า
// ==============================
function updateCart() {
  // ล้างตาราง
  tableBody.innerHTML = '';
  
  // คำนวณยอดรวม
  let subtotal = 0;

  // ถ้าตะกร้าว่าง แสดงข้อความ
  if (cart.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="4" style="text-align: center; color: var(--muted); padding: 40px;">
          ยังไม่มีรายการในตะกร้า
        </td>
      </tr>
    `;
  } else {
    // แสดงรายการในตะกร้า
    cart.forEach((item, index) => {
      subtotal += item.price;
      
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${index + 1}</td>
        <td>${item.name}</td>
        <td>${item.price.toLocaleString()} บาท</td>
        <td>
          <button class="btn-delete" data-index="${index}">ลบ</button>
        </td>
      `;
      tableBody.appendChild(row);
    });
  }

  // คำนวณ VAT และยอดรวมสุทธิ
  const vat = subtotal * 0.07;
  const total = subtotal + vat;

  // อัพเดทการแสดงผล
  if (subtotalSpan) subtotalSpan.textContent = subtotal.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  if (vatSpan) vatSpan.textContent = vat.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  if (totalSpan) totalSpan.textContent = total.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

  // เพิ่ม event listener สำหรับปุ่มลบ
  attachDeleteButtons();
}

// ==============================
// ฟังก์ชันจัดการปุ่มลบ
// ==============================
function attachDeleteButtons() {
  const deleteButtons = document.querySelectorAll('.btn-delete');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const index = parseInt(this.getAttribute('data-index'));
      removeFromCart(index);
    });
  });
}

// ==============================
// เพิ่มสินค้าเข้าตะกร้า
// ==============================
function addToCart(name, price) {
  cart.push({ 
    name: name, 
    price: parseFloat(price) 
  });
  updateCart();
  showNotification(`เพิ่ม "${name}" เข้าตะกร้าแล้ว`);
}

// ==============================
// ลบสินค้าออกจากตะกร้า
// ==============================
function removeFromCart(index) {
  const item = cart[index];
  cart.splice(index, 1);
  updateCart();
  showNotification(`ลบ "${item.name}" ออกจากตะกร้าแล้ว`);
}

// ==============================
// แสดงการแจ้งเตือนแบบง่าย
// ==============================
function showNotification(message) {
  alertMessage.textContent = message;
  alertPopup.classList.add('show');
  
  // ปิดอัตโนมัติหลัง 2 วินาที
  setTimeout(() => {
    alertPopup.classList.remove('show');
  }, 2000);
}

// ==============================
// คลิกการ์ดเมนูที่มีอยู่แล้ว
// ==============================
cards.forEach(card => {
  card.addEventListener('click', function() {
    const menuName = this.textContent.trim();
    const menuPrice = 50; // ราคาเริ่มต้น (ควรดึงจาก database จริงๆ)
    addToCart(menuName, menuPrice);
  });
});

// ==============================
// Modal: เปิด/ปิด
// ==============================
if (addMenuBtn) {
  addMenuBtn.addEventListener('click', () => {
    modalAdd.classList.add('show');
    document.body.classList.add('modal-open');
  });
}

if (closeModalBtn) {
  closeModalBtn.addEventListener('click', () => {
    closeModal();
  });
}

function closeModal() {
  modalAdd.classList.remove('show');
  document.body.classList.remove('modal-open');
  resetForm();
}

// ปิด modal เมื่อคลิกนอก modal-content
modalAdd.addEventListener('click', (e) => {
  if (e.target === modalAdd) {
    closeModal();
  }
});

// ปิด modal ด้วย ESC key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modalAdd.classList.contains('show')) {
    closeModal();
  }
});

// ==============================
// รีเซ็ตฟอร์ม
// ==============================
function resetForm() {
  if (addMenuForm) {
    addMenuForm.reset();
  }
}

// ==============================
// บันทึกเมนูใหม่
// ==============================
if (addMenuForm) {
  addMenuForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const menuName = document.getElementById('new-menu-name').value.trim();
    const menuPrice = document.getElementById('new-menu-price').value;
    const menuDesc = document.getElementById('new-menu-desc').value.trim();
    const menuImage = document.getElementById('new-menu-image').files[0];

    // ตรวจสอบข้อมูล
    if (!menuName || !menuPrice) {
      showNotification('❌ กรุณากรอกชื่อเมนูและราคา');
      return;
    }

    // ตรวจสอบราคา
    if (parseFloat(menuPrice) <= 0) {
      showNotification('❌ ราคาต้องมากกว่า 0');
      return;
    }

    // แสดงสถานะกำลังบันทึก
    saveMenuBtn.disabled = true;
    saveMenuBtn.textContent = 'กำลังบันทึก...';

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
        // ปิด modal
        closeModal();
        
        // แสดงการแจ้งเตือนสำเร็จ
        showNotification('✓ เพิ่มเมนูใหม่สำเร็จ');
        
        // รีโหลดหน้าหลังจาก 1.5 วินาที
        setTimeout(() => {
          location.reload();
        }, 1500);
      } else {
        showNotification('❌ ' + (result.message || 'เกิดข้อผิดพลาด'));
      }
    } catch (error) {
      console.error('Error:', error);
      showNotification('❌ เกิดข้อผิดพลาดในการเชื่อมต่อ');
    } finally {
      // คืนสถานะปุ่ม
      saveMenuBtn.disabled = false;
      saveMenuBtn.textContent = 'เพิ่มเมนู';
    }
  });
}

// ==============================
// ปิด Alert Popup
// ==============================
if (closeAlertBtn) {
  closeAlertBtn.addEventListener('click', () => {
    alertPopup.classList.remove('show');
  });
}

// ปิด alert เมื่อคลิกนอก modal
alertPopup.addEventListener('click', (e) => {
  if (e.target === alertPopup) {
    alertPopup.classList.remove('show');
  }
});

// ==============================
// ลบทั้งหมด
// ==============================
if (clearAllBtn) {
  clearAllBtn.addEventListener('click', () => {
    if (cart.length === 0) {
      showNotification('ตะกร้าว่างอยู่แล้ว');
      return;
    }
    
    if (confirm('ต้องการลบรายการทั้งหมดใช่หรือไม่?')) {
      cart = [];
      updateCart();
      showNotification('ลบรายการทั้งหมดแล้ว');
    }
  });
}

// ==============================
// ยืนยันการสั่งซื้อ
// ==============================
if (confirmOrderBtn) {
  confirmOrderBtn.addEventListener('click', () => {
    if (cart.length === 0) {
      showNotification('❌ กรุณาเพิ่มสินค้าในตะกร้าก่อน');
      return;
    }
    
    if (confirm('ยืนยันการสั่งซื้อหรือไม่?')) {
      // ส่งข้อมูลไปยัง backend
      console.log('สั่งซื้อ:', cart);
      showNotification('✓ สั่งซื้อสำเร็จ!');
      
      // ล้างตะกร้า
      setTimeout(() => {
        cart = [];
        updateCart();
      }, 1500);
    }
  });
}

// ==============================
// เริ่มต้นระบบ
// ==============================
document.addEventListener('DOMContentLoaded', () => {
  updateCart();
  console.log('✓ ระบบพร้อมใช้งาน');
});