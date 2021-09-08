window.Bootstrap = require("bootstrap");

let toastElList = [].slice.call(document.querySelectorAll('.toast'))
let toastList = toastElList.map(function (toastEl) {
    return new window.Bootstrap.Toast(toastEl, {});
})
