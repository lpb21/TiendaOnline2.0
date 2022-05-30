'use strict';

const searchBtn = document.querySelector('.search-btn');
const inputSearch = document.querySelector('#productName');
const insertDataMsg = document.querySelector('.alert-danger');
const isCollapse = document.querySelector('#isCollapse');
const advanceSearch = document.querySelector('#advanceSearch');

const onlyNumbers = function (el) {
    return el.value.replace(/\D/g, ''); // REGEX FOR NON DIGIT VALUE
};

const validateForm = function () {

    if (!inputSearch.value) {
        inputSearch.classList.add('is-invalid');
        insertDataMsg.classList.remove('hidden');
        // console.log('Empty!!');
    } else {
        // console.log(e.target);
        if (advanceSearch.classList.contains('show')) isCollapse.value = "show";
        document.querySelector('#searchProducts').submit();
    }
};

searchBtn.addEventListener('click', function (e) {
    e.preventDefault();
    validateForm();

});

inputSearch.addEventListener('keydown', function (e) {
    // console.log(e.key);
    inputSearch.classList.remove('is-invalid');
    insertDataMsg.classList.add('hidden');

    if (e.key === 'Enter') validateForm();

});

// ONLY ACCEPTS NUMBERS
document.querySelectorAll('.price').forEach(el => el.addEventListener('keydown', function (e) {
    e.target.value = onlyNumbers(e.target);
    // console.log('Just checking');
})
);
