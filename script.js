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

// Dropdown Elements
const dropdownBtn = document.getElementById('dropdownBtn');
const dropdownPanel = document.getElementById('dropdownPanel');

// ตะกร้าสินค้า
let cart = [];

// ==============================
// ฟังก์ชันอัพเดทตะกร้า
// ==============================
function updateCart() {
  if (!tableBody) return;
  
  tableBody.innerHTML = '';
  let subtotal = 0;

  if (cart.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="4" style="text-align: center; color: var(--muted); padding: 40px;">
          ยังไม่มีรายการในตะกร้า
        </td>
      </tr>
    `;
  } else {
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

  const vat = subtotal * 0.07;
  const total = subtotal + vat;

  if (subtotalSpan) subtotalSpan.textContent = subtotal.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  if (vatSpan) vatSpan.textContent = vat.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  if (totalSpan) totalSpan.textContent = total.toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

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
  if (alertMessage && alertPopup) {
    showNotification(`เพิ่ม "${name}" เข้าตะกร้าแล้ว`);
  }
}

// ==============================
// ลบสินค้าออกจากตะกร้า
// ==============================
function removeFromCart(index) {
  const item = cart[index];
  cart.splice(index, 1);
  updateCart();
  if (alertMessage && alertPopup) {
    showNotification(`ลบ "${item.name}" ออกจากตะกร้าแล้ว`);
  }
}

// ==============================
// แสดงการแจ้งเตือนแบบง่าย
// ==============================
function showNotification(message) {
  if (!alertMessage || !alertPopup) return;
  
  alertMessage.textContent = message;
  alertPopup.classList.add('show');
  
  setTimeout(() => {
    alertPopup.classList.remove('show');
  }, 2000);
}

// ==============================
// คลิกการ์ดเมนูที่มีอยู่แล้ว
// ==============================
if (cards.length > 0) {
  cards.forEach(card => {
    card.addEventListener('click', function() {
      const menuName = this.textContent.trim();
      const menuPrice = 50;
      addToCart(menuName, menuPrice);
    });
  });
}

// ==============================
// Modal: เปิด/ปิด
// ==============================
if (addMenuBtn && modalAdd) {
  addMenuBtn.addEventListener('click', () => {
    modalAdd.classList.add('show');
    document.body.classList.add('modal-open');
  });
}

if (closeModalBtn && modalAdd) {
  closeModalBtn.addEventListener('click', () => {
    closeModal();
  });
}

function closeModal() {
  if (modalAdd) {
    modalAdd.classList.remove('show');
  }
  document.body.classList.remove('modal-open');
  resetForm();
}

// เช็คว่ามี modalAdd ก่อนเพิ่ม event listener
if (modalAdd) {
  modalAdd.addEventListener('click', (e) => {
    if (e.target === modalAdd) {
      closeModal();
    }
  });
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modalAdd && modalAdd.classList.contains('show')) {
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

    if (!menuName || !menuPrice) {
      showNotification('❌ กรุณากรอกชื่อเมนูและราคา');
      return;
    }

    if (parseFloat(menuPrice) <= 0) {
      showNotification('❌ ราคาต้องมากกว่า 0');
      return;
    }

    saveMenuBtn.disabled = true;
    saveMenuBtn.textContent = 'กำลังบันทึก...';

    const formData = new FormData();
    formData.append('menuName', menuName);
    formData.append('menuPrice', menuPrice);
    formData.append('menuDesc', menuDesc);
    if (menuImage) {
      formData.append('menuImage', menuImage);
    }

    try {
      const response = await fetch('add_menu.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        closeModal();
        showNotification('✓ เพิ่มเมนูใหม่สำเร็จ');
        
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
      saveMenuBtn.disabled = false;
      saveMenuBtn.textContent = 'เพิ่มเมนู';
    }
  });
}

// ==============================
// ปิด Alert Popup
// ==============================
if (closeAlertBtn && alertPopup) {
  closeAlertBtn.addEventListener('click', () => {
    alertPopup.classList.remove('show');
  });
}

if (alertPopup) {
  alertPopup.addEventListener('click', (e) => {
    if (e.target === alertPopup) {
      alertPopup.classList.remove('show');
    }
  });
}

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
      console.log('สั่งซื้อ:', cart);
      showNotification('✓ สั่งซื้อสำเร็จ!');
      
      setTimeout(() => {
        cart = [];
        updateCart();
      }, 1500);
    }
  });
}

// ==============================
// Dropdown Menu
// ==============================
if (dropdownBtn && dropdownPanel) {
  dropdownBtn.addEventListener('click', () => {
    console.log('Dropdown clicked');
    dropdownPanel.classList.toggle('show');
    console.log('Dropdown show class:', dropdownPanel.classList.contains('show'));
  });
}

document.addEventListener('click', (e) => {
  if (dropdownPanel && !e.target.closest('.dropdown-category-wrapper')) {
    dropdownPanel.classList.remove('show');
  }
});

// ==============================
// Slideshow - เช็คว่ามี slides ก่อน
// ==============================
const slides = document.getElementsByClassName("mySlides");

if (slides.length > 0) {
  let slideIndex = 1;
  showSlides(slideIndex);

  // ทำให้ฟังก์ชันเป็น global เพื่อให้ onclick ใน HTML ใช้ได้
  window.plusSlides = function(n) {
    showSlides(slideIndex += n);
  }

  window.currentSlide = function(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    
    if (slides.length === 0) return;
    
    if (n > slides.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = slides.length}
    
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
    }
    
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    
    if (slides[slideIndex-1]) {
      slides[slideIndex-1].style.display = "block";
    }
    
    if (dots[slideIndex-1]) {
      dots[slideIndex-1].className += " active";
    }
  }

  // Auto Slideshow (ทุก 5 วินาที)
  setInterval(() => {
    slideIndex++;
    showSlides(slideIndex);
  }, 5000);
}

// ==============================
// เริ่มต้นระบบ
// ==============================
document.addEventListener('DOMContentLoaded', () => {
  updateCart();
  console.log('✓ ระบบพร้อมใช้งาน');
});

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