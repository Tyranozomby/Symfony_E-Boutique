const cartAdd = document.getElementById('cart-add');
const cartRemove = document.getElementById('cart-remove');

cartAdd.addEventListener('submit', addToCart);
cartRemove.addEventListener('submit', removeFromCart);

const cart = JSON.parse(localStorage.getItem('cart') || '{}');
console.log(cart, cartAdd.dataset.id)
if (cart[cartAdd.dataset.id]) {
    cartAdd.style.display = 'none';
    cartRemove.style.display = 'block';
} else {
    cartAdd.style.display = 'block';
    cartRemove.style.display = 'none';
}


function addToCart(event) {
    event.preventDefault();
    console.log('Add to cart')

    const data = new FormData(event.target);

    const id = parseInt(data.get('id'));
    const price = parseFloat(data.get('price'));
    const name = data.get('name');
    const quantity = parseInt(data.get('quantity'));

    cart[id] = {
        price: price,
        name: name,
        quantity: quantity,
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    // reload page
    location.reload();
}

function removeFromCart(event) {
    event.preventDefault();
    console.log('Remove from cart')

    const data = new FormData(event.target);

    delete cart[data.get('id')];

    localStorage.setItem('cart', JSON.stringify(cart));

    // reload page
    location.reload();
}