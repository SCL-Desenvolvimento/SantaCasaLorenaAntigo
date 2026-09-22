(() => {
  const portal = document.querySelector("#portal-transparencia");
  if (!portal) return;
  const search = portal.querySelector("#tp-search");
  const category = portal.querySelector("#tp-category");
  const documents = [...portal.querySelectorAll(".tp-document")];
  const groups = [...portal.querySelectorAll("details")];
  const sections = [...portal.querySelectorAll(".tp-section")];
  const normalize = (value) =>
    value
      .toLocaleLowerCase("pt-BR")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-z0-9]+/g, " ")
      .trim();
  const index = new Map(
    documents.map((link) => {
      const titles = [];
      for (
        let parent = link.parentElement;
        parent && parent !== portal;
        parent = parent.parentElement
      ) {
        if (parent.matches("details"))
          titles.push(parent.querySelector(".tp-summary-title").textContent);
      }
      return [
        link,
        normalize(
          link.dataset.search +
            " " +
            titles.join(" ") +
            " " +
            link.closest(".tp-section").querySelector("h2").textContent,
        ),
      ];
    }),
  );
  let previousState = null;
  const countText = (count) =>
    `${count} ${count === 1 ? "documento" : "documentos"}`;
  function filter() {
    const words = normalize(search.value).split(" ").filter(Boolean);
    const active = words.length > 0 || category.value !== "";
    if (active && !previousState)
      previousState = new Map(groups.map((group) => [group, group.open]));
    let count = 0;
    for (const link of documents) {
      link.hidden = !(
        words.every((word) => index.get(link).includes(word)) &&
        (!category.value ||
          link.closest(".tp-section").dataset.category === category.value)
      );
      if (!link.hidden) count++;
    }
    for (const group of groups) {
      const matches = [...group.querySelectorAll(".tp-document")].filter(
        (link) => !link.hidden,
      ).length;
      group.hidden = matches === 0;
      group.querySelector(".tp-count").textContent = countText(matches);
      if (active) group.open = matches > 0;
      else if (previousState) group.open = previousState.get(group);
    }
    if (!active) previousState = null;
    for (const section of sections)
      section.hidden = ![...section.querySelectorAll(".tp-document")].some(
        (link) => !link.hidden,
      );
    portal.querySelector("#tp-result-count").textContent =
      `${countText(count)} ${active ? "encontrados" : "disponíveis"}`;
    portal.querySelector("#tp-no-results").hidden = count > 0;
  }
  search.addEventListener("input", filter);
  category.addEventListener("change", filter);
  portal.querySelector("#tp-search-clear").addEventListener("click", () => {
    search.value = "";
    category.value = "";
    filter();
    search.focus();
  });
  search.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      search.value = "";
      filter();
    }
  });
  for (const action of ["expand", "collapse"])
    portal.querySelector(`#tp-${action}`).addEventListener("click", () =>
      groups
        .filter((group) => !group.hidden)
        .forEach((group) => {
          group.open = action === "expand";
        }),
    );
  portal.querySelector(".tp-tools").hidden = false;
  portal.querySelector(".tp-expand-tools").hidden = false;
  filter();
})();
