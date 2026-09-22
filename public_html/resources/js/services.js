"use strict";
document.querySelectorAll("[data-service-directory]").forEach((directory) => {
  const input = directory.querySelector("[data-service-search]");
  if (!input) return;
  const items = [...directory.querySelectorAll("[data-service-item]")];
  const normalize = (text) =>
    text
      .toLocaleLowerCase("pt-BR")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  const labels = new Map(
    items.map((item) => [item, normalize(item.textContent)]),
  );
  let state = null;
  function filter() {
    const words = normalize(input.value.trim()).split(/\s+/).filter(Boolean);
    if (words.length && !state)
      state = new Map(
        items
          .filter((item) => item.matches("details"))
          .map((item) => [item, item.open]),
      );
    let total = 0;
    for (const item of items) {
      item.hidden = !words.every((word) => labels.get(item).includes(word));
      if (!item.hidden) total++;
      if (item.matches("details")) {
        if (words.length) item.open = !item.hidden;
        else if (state) item.open = state.get(item);
      }
    }
    if (!words.length) state = null;
    directory.querySelector("[data-service-count]").textContent =
      `${total} ${total === 1 ? "resultado" : "resultados"}`;
    directory.querySelector("[data-service-empty]").hidden = total > 0;
  }
  directory.querySelector("[data-service-controls]").hidden = false;
  const tools = directory.querySelector("[data-service-expand-tools]");
  if (tools) {
    tools.hidden = false;
    for (const action of ["expand", "collapse"])
      tools
        .querySelector(`[data-service-${action}]`)
        .addEventListener("click", () =>
          items
            .filter((item) => !item.hidden && item.matches("details"))
            .forEach((item) => (item.open = action === "expand")),
        );
  }
  input.addEventListener("input", filter);
  input.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      input.value = "";
      filter();
    }
  });
  directory
    .querySelector("[data-service-clear]")
    .addEventListener("click", () => {
      input.value = "";
      filter();
      input.focus();
    });
  filter();
});
