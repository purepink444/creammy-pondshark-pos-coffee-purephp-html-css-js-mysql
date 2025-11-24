// ==============================
// ตัวแปรหลัก
// ==============================
const cards = document.querySelectorAll('.gallery .card');
const tableBody = document.querySelector('#cart-body');
const totalsDiv = document.getElementById('totals');
const clearAllBtn = document.getElementById('clear-all');

let cart = [];

// ==============================
// Update cart
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

// คลิกการ์ดเมนูเดิม
cards.forEach(card => {
  card.addEventListener('click', () => {
    addToCart(card.textContent.trim(), 50);
  });
});

// ==============================
// Modal: element
// ==============================
const modal = document.getElementById('modal-add');
const addBtn = document.getElementById('add-menu');
const closeModal = document.getElementById('close-modal');
const saveMenu = document.getElementById('save-menu');

// เปิด modal
addBtn.onclick = () => {
  modal.classList.add('show');
};

// ปิด modal
closeModal.onclick = () => {
  modal.classList.remove('show');
};

// ==============================
// Popup Alert
// ==============================
const alertPopup = document.getElementById("alert-popup");
const closeAlertBtn = document.getElementById("close-alert");

function showAlert() {
  alertPopup.style.display = "flex";
}

closeAlertBtn.addEventListener("click", () => {
  alertPopup.style.display = "none";
});

// ==============================
// บันทึกเมนูใหม่
// ==============================
saveMenu.onclick = () => {
  const name = document.getElementById('new-menu-name').value.trim();
  const price = parseFloat(document.getElementById('new-menu-price').value);

  if (!name || isNaN(price)) {
    alert("กรุณากรอกชื่อเมนูและราคา");
    return;
  }

  // สร้างการ์ดใหม่ใน gallery
  const newCard = document.createElement('div');
  newCard.className = 'card';
  newCard.textContent = name;

  newCard.onclick = () => addToCart(name, price);

  document.querySelector('.gallery').appendChild(newCard);

  // ปิด modal
  modal.classList.remove('show');

  // ล้าง input
  document.getElementById('new-menu-name').value = "";
  document.getElementById('new-menu-price').value = "";

  // แสดง popup สำเร็จ
  showAlert();
};

// ==============================
// ลบทั้งหมด
// ==============================
clearAllBtn.onclick = () => {
  cart = [];
  updateCart();
};
