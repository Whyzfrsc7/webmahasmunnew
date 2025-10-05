const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
const closeBtn = document.getElementById('closeBtn');

function openMenu() {
    mobileMenu.classList.remove('translate-x-full');
    mobileMenu.classList.add('translate-x-0');
}

function closeMenu() {
    mobileMenu.classList.remove('translate-x-0');
    mobileMenu.classList.add('translate-x-full');
}

hamburgerBtn.addEventListener('click', () => {

    if (mobileMenu.classList.contains('translate-x-full')) {
        openMenu();
    } else {
        closeMenu();
    }
});

if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
}

const dropdownBtns = document.querySelectorAll('#mobileMenu .dropdown-btn');
dropdownBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const ul = btn.nextElementSibling;
        const svg = btn.querySelector('svg'); 

        if (ul.style.maxHeight && ul.style.maxHeight !== '0px') {
            ul.style.maxHeight = '0px';
        } else {
            ul.style.maxHeight = ul.scrollHeight + 'px';
        }

        svg.classList.toggle('rotate-180');
    });
});

const loginBtn = document.getElementById('loginBtn');
const loginDropdown = document.getElementById('loginDropdown');

if (loginBtn && loginDropdown) {
    loginBtn.addEventListener('click', () => {
        loginDropdown.classList.toggle('opacity-0');
        loginDropdown.classList.toggle('scale-y-0');
        loginDropdown.classList.toggle('hidden');
    });
}

const slider = document.getElementById('slider');
const slides = slider ? slider.children : [];
let currentSlide = 0;

if (slider && slides.length > 0) {

    function goToSlide(index) {

        if (index < 0) {

            currentSlide = slides.length - 1;
        } else if (index >= slides.length) {

            currentSlide = 0;
        } else {
            currentSlide = index;
        }

        const offset = -currentSlide * 100;

        slider.style.transform = `translateX(${offset}%)`;
    }

    window.nextSlide = function() {
        goToSlide(currentSlide + 1);
    }

    window.prevSlide = function() {
        goToSlide(currentSlide - 1);
    }

    const slideInterval = setInterval(nextSlide, 2500);
}