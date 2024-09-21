const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);

let tabActive = $(".first__items.active");
let line = $(".line");

// Process active
let offsetLeft = tabActive?.offsetLeft;
let offsetWidth = tabActive?.offsetWidth;
line.style = `
        transform: translateX(calc(${offsetLeft}px - 2rem));
        width: ${offsetWidth}px;
    `;

// Process hover
$$(".first__items").forEach((item) => {
	item.addEventListener("mouseenter", (e) => {
		let offsetLeft = e.target.offsetLeft;
		let offsetWidth = e.target.offsetWidth;

		line.style = `
                transform: translateX(calc(${offsetLeft}px - 2rem));
                width: ${offsetWidth}px;
            `;
	});

	item.addEventListener("mouseleave", (e) => {
		let offsetLeft = tabActive?.offsetLeft;
		let offsetWidth = tabActive?.offsetWidth;

		line.style = `
                transform: translateX(calc(${offsetLeft}px - 2rem));
                width: ${offsetWidth}px;
            `;
	});
});
