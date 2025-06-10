<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Literasi Keuangan - U-Save</title>
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
    <link rel="stylesheet" href="css/style.css" />
    <!-- TinyMCE Editor -->
    <script
      src="https://cdn.tiny.cloud/1/jea0x5zq1xgq01p1iwsrad0tfmou8hl5hjj2ve8ojokkd0ld/tinymce/7/tinymce.min.js"
      referrerpolicy="origin"
    ></script>
    <script>
      tinymce.init({
        selector: "#literacyContent",
        plugins: "link lists table code help",
        toolbar:
          "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | code help",
        height: 500,
      });
    </script>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar would be the same as index.html -->

        <!-- Main Content -->
        <div class="col-lg-10 main-content" id="mainContent">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 id="literacyTitle">Edit Literasi Keuangan</h2>
            <button class="btn btn-primary" id="saveContent">
              <i class="bi bi-save"></i> Simpan Perubahan
            </button>
          </div>

          <div class="card">
            <div class="card-body">
              <form id="literacyForm">
                <div class="mb-3">
                  <label for="literacyTitleInput" class="form-label"
                    >Judul Konten</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="literacyTitleInput"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="literacyContent" class="form-label"
                    >Isi Konten</label
                  >
                  <textarea
                    id="literacyContent"
                    name="literacyContent"
                  ></textarea>
                </div>
                <div class="mb-3">
                  <label for="literacyImage" class="form-label"
                    >Gambar Ilustrasi</label
                  >
                  <input
                    type="file"
                    class="form-control"
                    id="literacyImage"
                    accept="image/*"
                  />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="script.js"></script>
  </body>
</html>
