"use strict";
document.documentElement.classList.add("js");
const navigation = document.getElementById("site-navigation");
const menuToggle = document.querySelector(".menu-toggle");
const dropdowns = [...document.querySelectorAll(".nav-dropdown")];
if (navigation && menuToggle) {
  menuToggle.hidden = false;
  const closeMenu = () => {
    navigation.classList.remove("is-open");
    menuToggle.setAttribute("aria-expanded", "false");
    dropdowns.forEach((item) => {
      item.open = false;
    });
  };
  menuToggle.addEventListener("click", () => {
    const expanded = menuToggle.getAttribute("aria-expanded") !== "true";
    menuToggle.setAttribute("aria-expanded", String(expanded));
    navigation.classList.toggle("is-open", expanded);
  });
  dropdowns.forEach((item) =>
    item.addEventListener("toggle", () => {
      if (item.open)
        dropdowns.forEach((other) => {
          if (other !== item) other.open = false;
        });
    }),
  );
  document.addEventListener("click", (event) => {
    if (!event.target.closest(".site-header")) closeMenu();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    const activeDropdown = dropdowns.find((item) => item.open);
    if (activeDropdown) {
      activeDropdown.open = false;
      activeDropdown.querySelector("summary").focus();
    } else if (navigation.classList.contains("is-open")) {
      closeMenu();
      menuToggle.focus();
    }
  });
  window
    .matchMedia("(min-width: 1101px)")
    .addEventListener("change", closeMenu);
  const path = location.pathname.replace(/\/$/, "");
  navigation.querySelectorAll("a").forEach((link) => {
    if (
      link.origin === location.origin &&
      link.pathname.replace(/\/$/, "") === path
    ) {
      link.setAttribute("aria-current", "page");
    }
  });
}
// Legacy contact tabs already provide their own click handlers.
if (location.hash === "#trabalhe-conosco") {
  window.addEventListener("load", () =>
    document.getElementById("trabalhe-conosco")?.click(),
  );
}
