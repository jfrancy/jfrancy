(function () {
  const storageKey = "lake-zone-chemicals-content";
  const content = loadContent();

  function loadContent() {
    try {
      const saved = localStorage.getItem(storageKey);
      return saved ? JSON.parse(saved) : window.LZC_DEFAULT_CONTENT;
    } catch (error) {
      return window.LZC_DEFAULT_CONTENT;
    }
  }

  function setText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value;
  }

  function renderCompany() {
    document.title = `${content.company.name} | Chemical Supplier in Tanzania`;
    setText("aboutText", content.company.about);
    setText("statProducts", content.company.stats.products);
    setText("statRegions", content.company.stats.regions);
    setText("statSupport", content.company.stats.support);
    setText("locationText", content.company.location);
    setText("year", new Date().getFullYear());

    const phoneLink = document.getElementById("phoneLink");
    const emailLink = document.getElementById("emailLink");
    if (phoneLink) {
      phoneLink.textContent = content.company.phone;
      phoneLink.href = `tel:${content.company.phone.replace(/\s+/g, "")}`;
    }
    if (emailLink) {
      emailLink.textContent = content.company.email;
      emailLink.href = `mailto:${content.company.email}`;
    }
  }

  function productCard(product) {
    return `
      <article class="product-card" data-category="${product.category}">
        <span>${product.category}</span>
        <h3>${product.name}</h3>
        <p>${product.description}</p>
        <dl>
          <div><dt>Grade</dt><dd>${product.grade}</dd></div>
          <div><dt>Pack</dt><dd>${product.packaging}</dd></div>
        </dl>
      </article>
    `;
  }

  function renderProducts(filter = "all") {
    const grid = document.getElementById("productGrid");
    if (!grid) return;
    const products = filter === "all" ? content.products : content.products.filter((item) => item.category === filter);
    grid.innerHTML = products.map(productCard).join("");
  }

  function renderIndustries() {
    const grid = document.getElementById("industryGrid");
    if (!grid) return;
    grid.innerHTML = content.industries
      .map(
        (industry) => `
          <article class="industry-card">
            <h3>${industry.title}</h3>
            <p>${industry.text}</p>
          </article>
        `
      )
      .join("");
  }

  function renderInsights() {
    const grid = document.getElementById("insightGrid");
    if (!grid) return;
    grid.innerHTML = content.insights
      .map(
        (item) => `
          <article class="insight-card">
            <span>${item.date}</span>
            <h3>${item.title}</h3>
            <p>${item.text}</p>
          </article>
        `
      )
      .join("");
  }

  function setupFilters() {
    document.querySelectorAll(".filter").forEach((button) => {
      button.addEventListener("click", () => {
        document.querySelectorAll(".filter").forEach((item) => item.classList.remove("is-active"));
        button.classList.add("is-active");
        renderProducts(button.dataset.filter);
      });
    });
  }

  function setupQuoteForm() {
    const form = document.getElementById("quoteForm");
    if (!form) return;
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const formData = new FormData(form);
      const subject = encodeURIComponent(`Chemical supply request from ${formData.get("company") || formData.get("name")}`);
      const body = encodeURIComponent(
        `Name: ${formData.get("name")}\nCompany: ${formData.get("company")}\nProduct: ${formData.get("product")}\nDetails: ${formData.get("details")}`
      );
      window.location.href = `mailto:${content.company.email}?subject=${subject}&body=${body}`;
    });
  }

  function setupHeader() {
    const header = document.querySelector(".site-header");
    if (!header) return;
    const update = () => header.setAttribute("data-elevated", window.scrollY > 12 ? "true" : "false");
    update();
    window.addEventListener("scroll", update, { passive: true });
  }

  renderCompany();
  renderProducts();
  renderIndustries();
  renderInsights();
  setupFilters();
  setupQuoteForm();
  setupHeader();
})();
