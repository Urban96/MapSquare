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
          <li class="breadcrumb-item active" aria-current="page">Maps</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <h1 class="h2 d-inline vertical-center">My maps</h1>
                <!-- Button Modal -->
                <button type="button" class="btn btn-primary btn-blue mb-3 float-end" data-bs-toggle="modal" data-bs-target="#modal-new-map">
                  <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  New map
                </button>
              </div>
            </div>
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <!-- Modal Content -->
                <div class="modal fade" id="modal-new-map" tabindex="-1" role="dialog" aria-labelledby="modal-new-map" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="h6 modal-title">Create new map</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="newMapForm" action="" class="mt-4">
                        <div class="modal-body">
                          <!-- Form -->
                          <div class="mb-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name-new" name="name-new" aria-describedby="Name" placeholder="Enter the name of the map" required>
                          </div>
                          <div class="my-4">
                            <label for="textarea">Description <small>(optional)</small></label>
                            <textarea class="form-control" id="description-new" name="description-new" placeholder="Enter the description..." rows="2"></textarea>
                          </div>
                          <!-- End of Form -->
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnNewMap" name="btnNewMap">Create</button>
                          <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End of Modal Content -->
                <div class="table-responsive">
                  <table class="table table-striped table-hover" style="width:100%" id="dataTable" name="dataTable">
                    <thead><tr><th>Name</th><th>Description</th><th>Created</th></tr></thead>
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

  <!-- Sweet Alerts 2 -->
  <script src="vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <!-- Smooth scroll -->
  <script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

  <!-- Notyf -->
  <script src="vendor/notyf/notyf.min.js"></script>

  <!-- Volt JS -->
  <script src="assets/js/volt.js"></script>

  <!-- jQuery JS -->
  <script src="vendor/jquery/jquery-3.6.3.min.js"></script>

  <!-- Common JS -->
  <script src="assets/js/common.js"></script>

  <!-- Maps JS -->
  <script src="assets/js/maps.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" src="vendor/datatables/datatables.min.js"></script>


</body>

</html>
