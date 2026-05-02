document.querySelectorAll("[data-filter]").forEach((button) => {
  button.addEventListener("click", () => {
    const filter = button.dataset.filter;
    document.querySelectorAll("[data-filter]").forEach((item) => item.classList.remove("is-active"));
    button.classList.add("is-active");
    document.querySelectorAll("[data-category]").forEach((card) => {
      card.hidden = filter !== "all" && card.dataset.category !== filter;
    });
  });
});

const quoteForm = document.querySelector("#quoteForm");
if (quoteForm) {
  quoteForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const data = new FormData(quoteForm);
    const email = quoteForm.dataset.email;
    const subject = encodeURIComponent(`Chemical supply request from ${data.get("company") || data.get("name")}`);
    const body = encodeURIComponent(`Name: ${data.get("name")}\nCompany: ${data.get("company")}\nProduct: ${data.get("product")}\nDetails: ${data.get("details")}`);
    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
  });
}
