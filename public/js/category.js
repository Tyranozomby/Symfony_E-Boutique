document.querySelectorAll('.book').forEach(book => {
    const id = book.id.split('-')[1];
    const cart = JSON.parse(localStorage.getItem('cart'));
    if (!cart || !cart[id]) {
        book.querySelector('.fa-cart-circle-check').classList.remove('fa-cart-circle-check');
    }
})