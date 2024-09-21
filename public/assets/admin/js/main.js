const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);

$$(".sidebar__item").forEach((item) => {
    item.addEventListener("click", () => {
        $(".sidebar__item.selected")?.classList.remove("selected");

        item.classList.add("selected");
    });
});

$$(".sidebar__item-link").forEach((item) => {
    item.addEventListener("click", () => {
        let arrow = item.querySelector(".icon");
        let subitems = item.nextElementSibling;

        if (arrow.classList.contains("rotate")) {
            arrow.classList.remove("rotate");
            arrow.classList.add("no-rotate");
        } else {
            arrow.classList.add("rotate");
            arrow.classList.remove("no-rotate");
        }

        subitems.classList.toggle("sidebar__sub-items--active");
    });
});