const items = JSON.parse(localStorage.getItem('cart'));
document.getElementById('shop-list-count').innerText = Object.keys(items).length || '';