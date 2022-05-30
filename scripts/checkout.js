'use strict';

const checkoutForm = document.querySelector('#checkoutProducts');
const btnBuy = document.querySelector('.buy');
const btnDelete = document.querySelectorAll('.delete');
// console.log(btnDelete);

btnBuy.addEventListener('click', function (e) {
    e.preventDefault();
    console.log(e.target);
    checkoutForm.submit();
    // test 23
});

btnDelete.forEach(btn => btn.addEventListener('click', function (e) {
    const productEl = e.target.closest('section');
    const productId = productEl.dataset.product;
    const productTalla = productEl.dataset.talla;
    const deleteEl = document.querySelector('.delete');

    deleteEl.value = `${productId}-${productTalla}`;
    // console.log(e.target.getAttribute('index'), e.target.closest('section'));
    console.log(deleteEl, productId);
    // e.target.closest('section').remove();
    checkoutForm.submit();
}));

// console.log(btnBuy);