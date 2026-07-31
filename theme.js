// ============================================
// CampusFind — Theme Toggle Logic
// Pairs with theme.css. Load this normally
// (bottom of body, or with `defer`).
// The instant-apply snippet that prevents a
// flash of the wrong theme lives inline in
// <head> — see setup instructions.
// ============================================

document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("theme-toggle");
  if (!toggle) return;

  updateIcon();

  toggle.addEventListener("click", function () {
    const current = document.documentElement.getAttribute("data-theme");
    const next = current === "dark" ? "light" : "dark";
    document.documentElement.setAttribute("data-theme", next);
    localStorage.setItem("theme", next);
    updateIcon();
  });

  function updateIcon() {
    const theme = document.documentElement.getAttribute("data-theme");
    toggle.textContent = theme === "dark" ? "☀️" : "🌙";
    toggle.setAttribute(
      "aria-label",
      theme === "dark" ? "Switch to light mode" : "Switch to dark mode"
    );
  }
});
