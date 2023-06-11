const listDiv = document.getElementById('myList');
const header = listDiv.querySelector('.card-header');
const body = listDiv.querySelector('.card-body');
const footer = listDiv.querySelector('.card-footer');

const cart = JSON.parse(localStorage.getItem('cart'));

if (cart) {
    const ul = document.createElement('ul');
    ul.classList.add('list-group', 'list-group-flush');

    let total = 0;

    for (const id in cart) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

        const book = cart[id];
        a.href = `../books/${id}`;

        a.innerHTML = `
            <span>${book.name}</span>
            <span>${book.quantity} &times; $${book.price} = $${book.price * book.quantity}</span>  
        `;

        total += cart[id].price * cart[id].quantity

        li.appendChild(a);
        ul.appendChild(li);
    }

    body.appendChild(ul);

    const footerDiv = document.createElement('div');
    footerDiv.innerHTML = `
        <span>Total:</span>
        <span>$${total}</span>
    `;

    footer.insertAdjacentElement("afterbegin", footerDiv);
}

function pasfait() {
    alert("Pas encore fait. Déjà en retard, je stresse trop. J'aurais bien aimé plus de cours et de cas pratiques sur Symfony parce que là c'était vraiment léger\nMerci pour votre compréhension");
}