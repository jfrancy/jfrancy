(function () {
  const storageKey = "lake-zone-chemicals-content";
  let content = loadContent();

  function loadContent() {
    try {
      return JSON.parse(localStorage.getItem(storageKey)) || structuredClone(window.LZC_DEFAULT_CONTENT);
    } catch (error) {
      return structuredClone(window.LZC_DEFAULT_CONTENT);
    }
  }

  function saveContent() {
    readCompanyForm();
    localStorage.setItem(storageKey, JSON.stringify(content));
    showToast("Saved. Refresh the public site to see changes.");
  }

  function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("is-visible");
    window.setTimeout(() => toast.classList.remove("is-visible"), 2800);
  }

  function bindTabs() {
    document.querySelectorAll(".tab").forEach((tab) => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".tab").forEach((item) => item.classList.remove("is-active"));
        document.querySelectorAll(".admin-panel").forEach((panel) => panel.classList.add("is-hidden"));
        tab.classList.add("is-active");
        document.getElementById(`${tab.dataset.tab}Panel`).classList.remove("is-hidden");
      });
    });
  }

  function renderCompanyForm() {
    document.getElementById("companyName").value = content.company.name;
    document.getElementById("companyPhone").value = content.company.phone;
    document.getElementById("companyWhatsapp").value = content.company.whatsapp || content.company.phone;
    document.getElementById("companyEmail").value = content.company.email;
    document.getElementById("companyLocation").value = content.company.location;
    document.getElementById("companyMapUrl").value = content.company.mapUrl || "";
    document.getElementById("companyAbout").value = content.company.about;
    document.getElementById("seoTitle").value = content.company.seo?.title || "";
    document.getElementById("seoDescription").value = content.company.seo?.description || "";
    document.getElementById("seoUrl").value = content.company.seo?.url || "";
    document.getElementById("seoImage").value = content.company.seo?.image || "";
    document.getElementById("seoKeywords").value = content.company.seo?.keywords || "";
  }

  function readCompanyForm() {
    content.company.name = document.getElementById("companyName").value.trim();
    content.company.phone = document.getElementById("companyPhone").value.trim();
    content.company.whatsapp = document.getElementById("companyWhatsapp").value.trim();
    content.company.email = document.getElementById("companyEmail").value.trim();
    content.company.location = document.getElementById("companyLocation").value.trim();
    content.company.mapUrl = document.getElementById("companyMapUrl").value.trim();
    content.company.about = document.getElementById("companyAbout").value.trim();
    content.company.seo = {
      title: document.getElementById("seoTitle").value.trim(),
      description: document.getElementById("seoDescription").value.trim(),
      url: document.getElementById("seoUrl").value.trim(),
      image: document.getElementById("seoImage").value.trim(),
      keywords: document.getElementById("seoKeywords").value.trim()
    };
  }

  function renderEditableList(key, targetId, fields) {
    const target = document.getElementById(targetId);
    target.innerHTML = content[key]
      .map((item, index) => {
        const inputs = fields
          .map((field) => {
            const value = item[field.name] || "";
            if (field.type === "textarea") {
              return `<label class="${field.wide ? "wide" : ""}">${field.label}<textarea rows="3" data-key="${key}" data-index="${index}" data-field="${field.name}">${value}</textarea></label>`;
            }
            return `<label class="${field.wide ? "wide" : ""}">${field.label}<input value="${value}" data-key="${key}" data-index="${index}" data-field="${field.name}" /></label>`;
          })
          .join("");

        return `
          <article class="editor-card">
            <div class="editor-card-head">
              <strong>${item.name || item.title || "New item"}</strong>
              <button class="icon-button" data-remove="${key}" data-index="${index}" type="button" aria-label="Remove">×</button>
            </div>
            <div class="form-grid">${inputs}</div>
          </article>
        `;
      })
      .join("");
  }

  function renderEditors() {
    renderEditableList("products", "productsEditor", [
      { name: "name", label: "Product name" },
      { name: "category", label: "Category" },
      { name: "grade", label: "Grade" },
      { name: "packaging", label: "Packaging" },
      { name: "description", label: "Description", type: "textarea", wide: true }
    ]);
    renderEditableList("industries", "industriesEditor", [
      { name: "title", label: "Industry" },
      { name: "text", label: "Description", type: "textarea", wide: true }
    ]);
    renderEditableList("insights", "insightsEditor", [
      { name: "title", label: "Title" },
      { name: "date", label: "Label or date" },
      { name: "text", label: "Text", type: "textarea", wide: true }
    ]);
  }

  function bindEditorChanges() {
    document.addEventListener("input", (event) => {
      const input = event.target;
      if (!input.dataset.key) return;
      content[input.dataset.key][Number(input.dataset.index)][input.dataset.field] = input.value;
    });

    document.addEventListener("click", (event) => {
      const removeButton = event.target.closest("[data-remove]");
      if (removeButton) {
        const key = removeButton.dataset.remove;
        content[key].splice(Number(removeButton.dataset.index), 1);
        renderEditors();
        showToast("Item removed. Remember to save.");
      }

      const addButton = event.target.closest("[data-add]");
      if (addButton) {
        const key = addButton.dataset.add;
        const templates = {
          products: { name: "New Chemical", category: "Industrial", grade: "Grade", packaging: "Packaging", description: "Description" },
          industries: { title: "New Industry", text: "Description" },
          insights: { title: "New Insight", date: "Update", text: "Text" }
        };
        content[key].push(templates[key]);
        renderEditors();
        showToast("New item added. Remember to save.");
      }
    });
  }

  function bindImportExport() {
    document.getElementById("exportBtn").addEventListener("click", () => {
      readCompanyForm();
      const blob = new Blob([JSON.stringify(content, null, 2)], { type: "application/json" });
      const url = URL.createObjectURL(blob);
      const link = document.createElement("a");
      link.href = url;
      link.download = "lake-zone-chemicals-content.json";
      link.click();
      URL.revokeObjectURL(url);
    });

    document.getElementById("importInput").addEventListener("change", async (event) => {
      const [file] = event.target.files;
      if (!file) return;
      content = JSON.parse(await file.text());
      localStorage.setItem(storageKey, JSON.stringify(content));
      renderCompanyForm();
      renderEditors();
      showToast("Imported and saved.");
    });
  }

  document.getElementById("saveBtn").addEventListener("click", saveContent);
  bindTabs();
  bindEditorChanges();
  bindImportExport();
  renderCompanyForm();
  renderEditors();
})();
