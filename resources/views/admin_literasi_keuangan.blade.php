<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - U-Save</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin_literasi_keuangan.css" />
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 sidebar p-0" id="sidebar">
          <div class="d-flex flex-column flex-shrink-0 p-3 h-100">
            <a
              href="/"
              class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"
            >
              <span class="fs-4">
                <img
                  src="img/logo.png"
                  alt="Logo"
                  class="logo"
                />U-Save
              </span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item">
                <a
                  href="admin_dashboard.html"
                  class="nav-link link-dark"
                  id="dashboardTab"
                >
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="#   "
                  class="nav-link active"
                  id="literacyTab"
                  aria-current="page"
                >
                  <i class="bi bi-book me-2"></i>
                  Literasi Keuangan
                </a>
              </li>
              <li>
                <a
                  href="admin_budgeting.html"
                  class="nav-link link-dark"
                  id="budgetingTab"
                >
                  <i class="bi bi-calculator me-2"></i>
                  Budgeting
                </a>
              </li>
            </ul>
            <hr />
            <div class="dropup mt-auto">
              <a
                href="#"
                class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <img
                  src="img/photo-profile.jpg"
                  alt=""
                  width="32"
                  height="32"
                  class="rounded-circle me-2"
                />
                <strong>Admin</strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 main-content" id="mainContent">
          <h2 class="mb-4">Literasi Keuangan</h2>

          <!-- Financial Literacy Buttons -->
          <div class="row mb-4">
            <div class="col-md-4 mb-3">
              <a
                href="edit_literacy.html?type=dasar"
                class="card literacy-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i
                    class="bi bi-journal-bookmark literacy-icon text-primary"
                  ></i>
                  <h5 class="literacy-title">Pemahaman Dasar</h5>
                  <p class="literacy-desc">
                    Kelola konten literasi keuangan dasar
                  </p>
                </div>
              </a>
            </div>
            <div class="col-md-4 mb-3">
              <a
                href="edit_literacy.html?type=tabungan"
                class="card literacy-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-piggy-bank literacy-icon text-success"></i>
                  <h5 class="literacy-title">Tabungan & Pinjaman</h5>
                  <p class="literacy-desc">
                    Kelola konten tentang tabungan dan pinjaman
                  </p>
                </div>
              </a>
            </div>
            <div class="col-md-4 mb-3">
              <a
                href="edit_literacy.html?type=investasi"
                class="card literacy-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i
                    class="bi bi-graph-up-arrow literacy-icon text-warning"
                  ></i>
                  <h5 class="literacy-title">Investasi</h5>
                  <p class="literacy-desc">Kelola konten tentang investasi</p>
                </div>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a
                href="edit_literacy.html?type=asuransi"
                class="card literacy-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-shield-check literacy-icon text-info"></i>
                  <h5 class="literacy-title">Asuransi</h5>
                  <p class="literacy-desc">Kelola konten tentang asuransi</p>
                </div>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a
                href="edit_literacy.html?type=perencanaan"
                class="card literacy-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-calendar-check literacy-icon text-danger"></i>
                  <h5 class="literacy-title">Perencanaan Jangka Panjang</h5>
                  <p class="literacy-desc">
                    Kelola konten perencanaan keuangan
                  </p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="script.js"></script>
    <script>
      tinymce.init({
        selector: "textarea",
        plugins: [
          // Core editing features
          "anchor",
          "autolink",
          "charmap",
          "codesample",
          "emoticons",
          "image",
          "link",
          "lists",
          "media",
          "searchreplace",
          "table",
          "visualblocks",
          "wordcount",
          // Your account includes a free trial of TinyMCE premium features
          // Try the most popular premium features until Apr 28, 2025:
          "checklist",
          "mediaembed",
          "casechange",
          "formatpainter",
          "pageembed",
          "a11ychecker",
          "tinymcespellchecker",
          "permanentpen",
          "powerpaste",
          "advtable",
          "advcode",
          "editimage",
          "advtemplate",
          "ai",
          "mentions",
          "tinycomments",
          "tableofcontents",
          "footnotes",
          "mergetags",
          "autocorrect",
          "typography",
          "inlinecss",
          "markdown",
          "importword",
          "exportword",
          "exportpdf",
        ],
        toolbar:
          "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
        tinycomments_mode: "embedded",
        tinycomments_author: "Author name",
        mergetags_list: [
          { value: "First.Name", title: "First Name" },
          { value: "Email", title: "Email" },
        ],
        ai_request: (request, respondWith) =>
          respondWith.string(() =>
            Promise.reject("See docs to implement AI Assistant")
          ),
      });
    </script>
  </body>
</html>
