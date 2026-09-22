"use strict";
document.querySelectorAll("[data-about-gallery]").forEach((aboutGallery) => {
  const track = aboutGallery.querySelector(".about-gallery-track");
  const slides = [...track.querySelectorAll(".about-slide")];
  const links = slides.map((slide) => slide.querySelector("a"));
  const controls = aboutGallery.querySelector(".about-gallery-controls");
  const prev = controls.querySelector("[data-gallery-prev]");
  const next = controls.querySelector("[data-gallery-next]");
  const count = controls.querySelector("[data-gallery-count]");
  let index = 0;
  const update = () => {
    const maxScroll = track.scrollWidth - track.clientWidth;
    index =
      maxScroll > 1 && track.scrollLeft >= maxScroll - 2
        ? slides.length - 1
        : slides.reduce(
            (best, slide, i) =>
              Math.abs(slide.offsetLeft - track.scrollLeft) <
              Math.abs(slides[best].offsetLeft - track.scrollLeft)
                ? i
                : best,
            0,
          );
    prev.disabled = index === 0;
    next.disabled = index === slides.length - 1;
    count.textContent = `${index + 1} de ${slides.length}`;
  };
  const move = (target) =>
    track.scrollTo({
      left: slides[Math.max(0, Math.min(slides.length - 1, target))].offsetLeft,
      behavior: matchMedia("(prefers-reduced-motion: reduce)").matches
        ? "instant"
        : "smooth",
    });
  controls.hidden = slides.length < 2;
  prev.addEventListener("click", () => move(index - 1));
  next.addEventListener("click", () => move(index + 1));
  track.addEventListener("keydown", (event) => {
    if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
      event.preventDefault();
      move(index + (event.key === "ArrowRight" ? 1 : -1));
    }
  });
  let scrollTimer;
  track.addEventListener(
    "scroll",
    () => {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(update, 120);
    },
    { passive: true },
  );
  new ResizeObserver(update).observe(track);
  update();
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
      active = Math.max(0, Math.min(slides.length - 1, target));
      image.src = links[active].href;
      image.alt = links[active].querySelector("img").alt;
      caption.textContent =
        slides[active].querySelector("figcaption")?.textContent || image.alt;
      dialog.querySelector("[data-dialog-count]").textContent =
        `${active + 1} de ${slides.length}`;
      dialogPrev.disabled = active === 0;
      dialogNext.disabled = active === slides.length - 1;
    };
    dialog.querySelector(".about-dialog-controls").hidden = slides.length < 2;
    links.forEach((link, i) =>
      link.addEventListener("click", (event) => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)
          return;
        event.preventDefault();
        opener = link;
        show(i);
        dialog.showModal();
      }),
    );
    dialogPrev.addEventListener("click", () => show(active - 1));
    dialogNext.addEventListener("click", () => show(active + 1));
    dialog
      .querySelector("[data-dialog-close]")
      .addEventListener("click", () => dialog.close());
    dialog.addEventListener("close", () =>
      opener?.focus({ preventScroll: true }),
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
