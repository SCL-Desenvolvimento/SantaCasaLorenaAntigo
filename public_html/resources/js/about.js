"use strict";
document.querySelectorAll("[data-about-gallery]").forEach((aboutGallery) => {
  const track = aboutGallery.querySelector(".about-gallery-track");
  const slides = [...track.querySelectorAll(".about-slide")];
  const links = slides.map((slide) => slide.querySelector("a"));
  const controls = aboutGallery.querySelector(".about-gallery-controls");
  const carousel = window.SCLCarousel(aboutGallery,track,slides,controls);
  const dialog = aboutGallery.nextElementSibling?.matches(".about-lightbox")
    ? aboutGallery.nextElementSibling
    : null;
  if (dialog && typeof dialog.showModal === "function") {
    const image = dialog.querySelector("[data-dialog-image]");
    const caption = dialog.querySelector("[data-dialog-caption]");
    const dialogPrev = dialog.querySelector("[data-dialog-prev]");
    const dialogNext = dialog.querySelector("[data-dialog-next]");
    let active = 0;
    let opener;
    const show = (target) => {
      active = (target + slides.length) % slides.length;
      image.src = links[active].href;
      image.alt = links[active].querySelector("img").alt;
      caption.textContent =
        slides[active].querySelector("figcaption")?.textContent || image.alt;
      dialog.querySelector("[data-dialog-count]").textContent =
        `${active + 1} de ${slides.length}`;
      dialogPrev.disabled = slides.length < 2;
      dialogNext.disabled = slides.length < 2;
    };
    dialog.querySelector(".about-dialog-controls").hidden = slides.length < 2;
    links.forEach((link, i) =>
      link.addEventListener("click", (event) => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)
          return;
        event.preventDefault();
        opener = link;
        show(i);
        carousel?.suspend(true);
        dialog.showModal();
      }),
    );
    dialogPrev.addEventListener("click", () => show(active - 1));
    dialogNext.addEventListener("click", () => show(active + 1));
    dialog
      .querySelector("[data-dialog-close]")
      .addEventListener("click", () => dialog.close());
    dialog.addEventListener("close", () =>
      { carousel?.suspend(false); opener?.focus({ preventScroll: true }); },
    );
    dialog.addEventListener("click", (event) => {
      if (event.target === dialog) {
        const box = dialog.getBoundingClientRect();
        if (
          event.clientX < box.left ||
          event.clientX > box.right ||
          event.clientY < box.top ||
          event.clientY > box.bottom
        )
          dialog.close();
      }
    });
    dialog.addEventListener("keydown", (event) => {
      if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
        event.preventDefault();
        show(active + (event.key === "ArrowRight" ? 1 : -1));
      }
    });
  }
});
