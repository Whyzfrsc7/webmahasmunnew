  const texts = [
    "Welcome to MA Hasan Munadi",
    "Madrasah Multimedia",
    "Madrasah Siap Kerja",
    "Madrasah Entrepreneur",
    "Madrasah Mengaji"
  ];

  let textIndex = 0;
  const fadeText = document.getElementById("fadeText");

  function showText() {
    fadeText.classList.add("opacity-0");
    setTimeout(() => {
      textIndex = (textIndex + 1) % texts.length;
      fadeText.textContent = texts[textIndex];
      fadeText.classList.remove("opacity-0");
    }, 1000); // waktu fade out
  }

  // awal tampilkan teks
  fadeText.classList.remove("opacity-0");

  // tiap 4 detik ganti teks
  setInterval(showText, 5000);