<?php
require 'server/auth.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Primary Meta Tags -->
  <title>MapSquare.io</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="title" content="MapSquare.io">
  <meta name="author" content="Daniel Urban">
  <meta name="description" content="Web map platform for analyzing and sharing your geodata">
  <meta name="keywords" content="mapsquare, map, platform, analysis, data, geodata">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="assets/img/favicon/site.webmanifest">
  <link rel="mask-icon" href="assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Sweet Alert -->
  <link type="text/css" href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Notyf -->
  <link type="text/css" href="vendor/notyf/notyf.min.css" rel="stylesheet">

  <!-- Volt CSS -->
  <link type="text/css" href="css/volt.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables.min.css"/>


</head>

<body>

  <div id="loadingScreen" class="loadingScreen">
    <div class="spinner-border"></div>
  </div>

  <div id="menuDiv"></div>

  <main class="content">

    <div class="py-4">
      <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
          <li class="breadcrumb-item">
            <a href="dashboard">
              <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Layers</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <h1 class="h2 d-inline vertical-center">My layers</h1>
                <div class="dropdown float-end">
                  <button class="btn btn-primary btn-blue mb-3 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New layer
                  </button>
                  <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                    <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-layer-empty">
                      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                      </svg>
                      Empty layer
                    </a>
                    <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-layer-data">
                      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2z"/>
                      </svg>
                      From my data
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <!-- Modal Content -->
                <div class="modal fade" id="modal-layer-empty" tabindex="-1" role="dialog" aria-labelledby="modal-layer-empty" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="h6 modal-title">Create an empty layer</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="layerEmptyForm" action="" class="mt-4">
                        <div class="modal-body">
                          <!-- Form -->
                          <div class="mb-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name-new" name="name-new" aria-describedby="Name" placeholder="Enter the name of the layer" required>
                          </div>
                          <div class="my-4">
                            <label for="textarea">Description <small>(optional)</small></label>
                            <textarea class="form-control" id="description-new" name="description-new" placeholder="Enter the description..." rows="2"></textarea>
                          </div>
                          <!-- Form -->
                          <div class="mb-4">
                            <label class="my-1 me-2" for="type-new">Layer type</label>
                            <select class="form-select" id="type-new" name="type-new" aria-label="Layer type" required>
                              <option selected disabled value="">Please select one of these types</option>
                              <option value="Point">Point</option>
                              <option value="PolyLine">Line</option>
                              <option value="Polygon">Polygon</option>
                            </select>
                            <small id="typeHelp" class="form-text text-muted">Each layer can contain only points, lines or polygons.</small>
                          </div>
                          <!-- End of Form -->
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnUpload" name="btnUpload">Create</button>
                          <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End of Modal Content -->
                <div class="modal fade" id="modal-layer-data" tabindex="-1" role="dialog" aria-labelledby="modal-layer-data" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="h6 modal-title">Create a layer from my data</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="layerDataForm" action="" class="mt-4">
                        <div class="modal-body">
                          <!-- Form -->
                          <div class="mb-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name-data" aria-describedby="Name" placeholder="Enter the name of the layer" required>
                          </div>
                          <!-- Form -->
                          <div class="my-4">
                            <label for="textarea">Description <small>(optional)</small></label>
                            <textarea class="form-control" id="description-data" placeholder="Enter the description..." id="textarea" rows="2"></textarea>
                          </div>
                          <div class="mb-4">
                            <label class="my-1 me-2" for="source-data">Data source</label>
                            <select class="form-select" id="source-data" aria-label="Layer source" required>
                              <option selected disabled value="">Please select one of your data sources</option>
                            </select>
                            <small id="typeHelp" class="form-text text-muted">Please select one of your data sources.</small>
                          </div>
                          <!-- End of Form -->
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnUpload" name="btnUpload">Create</button>
                          <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End of Modal Content -->
                <div class="table-responsive">
                  <table class="table table-striped table-hover" style="width:100%" id="dataTable" name="dataTable">
                    <thead><tr><th>Name</th><th>Description</th><th>Feature type</th><th>Created</th></tr></thead>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <footer class="mt-4">
      <div class="row">
        <div class="container text-center">
          <p class="text-muted credit">© 2023 MapSquare.io. Theme by <span class="current-year"></span> <a class="text-primary fw-normal" href="https://themesberg.com" target="_blank">Themesberg</a></p>
        </div>
      </div>
    </footer>
  </main>

  <!-- Core -->
  <script src="vendor/@popperjs/core/dist/umd/popper.min.js"></script>
  <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- Vendor JS -->
  <script src="vendor/onscreen/dist/on-screen.umd.min.js"></script>

  <!-- Smooth scroll -->
  <script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

  <!-- Sweet Alerts 2 -->
  <script src="vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <!-- Notyf -->
  <script src="vendor/notyf/notyf.min.js"></script>

  <!-- Volt JS -->
  <script src="assets/js/volt.js"></script>

  <!-- jQuery JS -->
  <script src="vendor/jquery/jquery-3.6.3.min.js"></script>

  <!-- Common JS -->
  <script src="assets/js/common.js"></script>

  <!-- Layers JS -->
  <script src="assets/js/layers.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" src="vendor/datatables/datatables.min.js"></script>


</body>

</html>
