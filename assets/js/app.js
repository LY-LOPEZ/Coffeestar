function toggleDelivery(){
    const tipo = document.querySelector('[name="tipo"]').value;
    const mesa = document.getElementById('mesaBox');
    const delivery = document.getElementById('deliveryBox');
    if (mesa) mesa.style.display = tipo === 'mesa' ? 'block' : 'none';
    if (delivery) delivery.style.display = tipo === 'delivery' ? 'block' : 'none';
}

function addToCart(id, nombre, precio, tiempo) {
    let cart = JSON.parse(localStorage.getItem('coffestar_cart') || '[]');
    let item = cart.find(x => x.id === id);
    if (item) item.cantidad++;
    else cart.push({id, nombre, precio, tiempo, cantidad:1});
    localStorage.setItem('coffestar_cart', JSON.stringify(cart));
    renderCart();
}

function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem('coffestar_cart') || '[]');
    cart = cart.filter(x => x.id !== id);
    localStorage.setItem('coffestar_cart', JSON.stringify(cart));
    renderCart();
}

function renderCart() {
    const box = document.getElementById('cartBox');
    const input = document.getElementById('cartInput');
    const totalBox = document.getElementById('cartTotal');
    if (!box) return;
    let cart = JSON.parse(localStorage.getItem('coffestar_cart') || '[]');
    let total = 0;
    box.innerHTML = cart.length ? cart.map(i => {
        total += i.precio * i.cantidad;
        return `<div class="cart-item between"><div><b>${i.nombre}</b><br><span class="muted">${i.cantidad} x Bs. ${i.precio.toFixed(2)}</span></div><button type="button" onclick="removeFromCart(${i.id})">Quitar</button></div>`;
    }).join('') : '<p class="muted">Carrito vacío.</p>';
    if (input) input.value = JSON.stringify(cart);
    if (totalBox) totalBox.textContent = 'Bs. ' + total.toFixed(2);
}

function limpiarCarrito() {
    localStorage.removeItem('coffestar_cart');
}

document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    const tipo = document.querySelector('[name="tipo"]');
    if (tipo) {
        tipo.addEventListener('change', toggleDelivery);
        toggleDelivery();
    }
});
