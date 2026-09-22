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
  const updateCurrentLink = () => {
    const path = location.pathname
      .replace(/\/$/, "")
      .replace(/fale_conosco$/, "fale-conosco");
    const current = new URL(location.href);
    navigation.querySelectorAll("a").forEach((link) => {
      let matches =
        link.origin === location.origin &&
        link.pathname.replace(/\/$/, "") === path;
      if (matches && /fale-conosco$/.test(path)) {
        const target = new URL(link.href);
        matches =
          current.hash === "#localizacao"
            ? target.hash === "#localizacao"
            : target.hash !== "#localizacao" &&
              target.searchParams.get("canal") ===
                (current.searchParams.get("canal") || "contato");
      }
      if (matches) link.setAttribute("aria-current", "page");
      else link.removeAttribute("aria-current");
    });
  };
  navigation.addEventListener("click", (event) => {
    if (event.target.closest("a")) closeMenu();
  });
  window.addEventListener("hashchange", updateCurrentLink);
  updateCurrentLink();
}
