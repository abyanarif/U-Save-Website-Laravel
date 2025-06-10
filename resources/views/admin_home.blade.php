<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
    <title>Document</title>
  </head>
  <body>
    <main>
      <div
        class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary sidebarContainer"
        style="width: 280px"
        id="sidebar"
      >
        <a
          href="/"
          class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"
        >
          <svg
            class="bi pe-none me-2"
            width="40"
            height="32"
            aria-hidden="true"
          >
            <use xlink:href="#bootstrap"></use>
          </svg>
          <span class="fs-4"
            ><img
              src="img/logo.png"
              alt="Logo"
              class="logo"
            />U-Save</span
          >
        </a>
        <hr />
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item">
            <a href="#" class="nav-link active" aria-current="page">
              <svg
                class="bi pe-none me-2"
                width="16"
                height="16"
                aria-hidden="true"
              >
                <use xlink:href="#home"></use>
              </svg>
              Home
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-body-emphasis">
              <svg
                class="bi pe-none me-2"
                width="16"
                height="16"
                aria-hidden="true"
              >
                <use xlink:href="#speedometer2"></use>
              </svg>
              Dashboard
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-body-emphasis">
              <svg
                class="bi pe-none me-2"
                width="16"
                height="16"
                aria-hidden="true"
              >
                <use xlink:href="#table"></use>
              </svg>
              Orders
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-body-emphasis">
              <svg
                class="bi pe-none me-2"
                width="16"
                height="16"
                aria-hidden="true"
              >
                <use xlink:href="#grid"></use>
              </svg>
              Products
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-body-emphasis">
              <svg
                class="bi pe-none me-2"
                width="16"
                height="16"
                aria-hidden="true"
              >
                <use xlink:href="#people-circle"></use>
              </svg>
              Customers
            </a>
          </li>
        </ul>
        <hr />
        <div class="dropup">
          <a
            href="#"
            class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle"
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
            <strong>tina</strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-end text-small shadow">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
      </div>
      <div></div>
    </main>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
  </body>
</html>
