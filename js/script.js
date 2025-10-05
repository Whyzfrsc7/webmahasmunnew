  const loginBtn = document.getElementById("loginBtn");
  const loginDropdown = document.getElementById("loginDropdown");

  loginBtn.addEventListener("click", () => {
    if (loginDropdown.classList.contains("hidden")) {
      loginDropdown.classList.remove("hidden");
      setTimeout(() => {
        loginDropdown.classList.add("opacity-100", "scale-y-100");
        loginDropdown.classList.remove("opacity-0", "scale-y-0");
      }, 10);
    } else {
      loginDropdown.classList.add("opacity-0", "scale-y-0");
      loginDropdown.classList.remove("opacity-100", "scale-y-100");
      setTimeout(() => {
        loginDropdown.classList.add("hidden");
      }, 300);
    }
  });

  // Klik di luar = nutup
  document.addEventListener("click", (e) => {
    if (!loginBtn.contains(e.target) && !loginDropdown.contains(e.target)) {
      loginDropdown.classList.add("opacity-0", "scale-y-0");
      loginDropdown.classList.remove("opacity-100", "scale-y-100");
      setTimeout(() => {
        loginDropdown.classList.add("hidden");
      }, 300);
    }
  });