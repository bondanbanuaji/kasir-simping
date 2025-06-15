const words = [
    "Selamat Datang di Web Kasir Simping",
    "Lihat laporan penjualan dengan mudah",
    "Pantau semua aktivitas Toko di sini",
];

const typingSpeed = 100;
const eraseSpeed = 50;
const delayBetweenWords = 1500;

let currentWordIndex = 0;
let charIndex = 0;
let isDeleting = false;
const typedText = document.getElementById("typed-text");

function typeEffect() {
    const currentWord = words[currentWordIndex];
    if (!isDeleting) {
        // Mengetik
        typedText.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
        if (charIndex === currentWord.length) {
            isDeleting = true;
            setTimeout(typeEffect, delayBetweenWords);
        } else {
            setTimeout(typeEffect, typingSpeed);
        }
    } else {
        // Menghapus
        typedText.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
        if (charIndex === 0) {
            isDeleting = false;
            currentWordIndex = (currentWordIndex + 1) % words.length;
        }
        setTimeout(typeEffect, eraseSpeed);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    typeEffect();
});