document.addEventListener("DOMContentLoaded", () => {
  const menuButton = document.getElementById("menuButton");
  const navbar = document.getElementById("navbar");
  const sidebar = document.querySelector(".sidebar");

  menuButton.addEventListener("click", () => {
    navbar.classList.toggle("navbar-active"); // Toggle tampilan navbar
    sidebar.classList.toggle("sidebar-shift"); // Toggle pergeseran sidebar
  });
});
