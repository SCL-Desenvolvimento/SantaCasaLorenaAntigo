(() => {
  // Preserve incoming links from the existing footer and older contact pages.
  const channels = {
    "#trabalhe-conosco": "trabalhe_conosco",
    "#pesquisa": "pesquisa",
    "#pesquisa-atendimento": "pesquisa",
    "#ouvidoria": "contato",
  };
  const channel = channels[location.hash];
  if (channel && document.querySelector(".community-channels")) {
    const target = new URL(location.href);
    if (target.searchParams.get("canal") !== channel) {
      target.searchParams.set("canal", channel);
      target.hash = "formulario";
      location.replace(target.href);
      return;
    }
  }
  const focusLocation = () => {
    if (location.hash === "#localizacao")
      document.getElementById("localizacao")?.focus({ preventScroll: true });
  };
  window.addEventListener("hashchange", focusLocation);
  focusLocation();
  const errors = document.querySelector("[data-form-errors]");
  if (errors) errors.focus();
  const facebook = document.querySelector("[data-share-article]");
  if (facebook) {
    const share = new URL(facebook.href);
    share.searchParams.set(
      "u",
      new URL(facebook.dataset.shareArticle, location.href).href,
    );
    facebook.href = share.href;
  }
  const copy = document.querySelector("[data-copy-article]");
  if (copy && navigator.clipboard && window.isSecureContext) {
    copy.hidden = false;
    copy.addEventListener("click", async () => {
      const status = document.querySelector("[data-copy-status]");
      try {
        await navigator.clipboard.writeText(
          new URL(copy.dataset.copyArticle, location.href).href,
        );
        status.textContent = "Link copiado!";
      } catch {
        status.textContent =
          "Não foi possível copiar. Copie o endereço na barra do navegador.";
      }
    });
  }
  const dialog = document.querySelector(".article-lightbox");
  if (dialog && typeof dialog.showModal === "function") {
    let opener;
    document.querySelectorAll(".article-image-link").forEach((link) =>
      link.addEventListener("click", (event) => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)
          return;
        event.preventDefault();
        opener = link;
        const image = dialog.querySelector("img");
        image.src = link.href;
        image.alt = link.querySelector("img")?.alt || "Imagem da notícia";
        dialog.showModal();
      }),
    );
    dialog
      .querySelector("[data-article-close]")
      .addEventListener("click", () => dialog.close());
    dialog.addEventListener("close", () => opener?.focus());
  }
})();
