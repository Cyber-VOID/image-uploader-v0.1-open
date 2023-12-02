import './bootstrap';
import Swal from "sweetalert2";


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        toast.addEventListener('click', Swal.close)
    }
})

