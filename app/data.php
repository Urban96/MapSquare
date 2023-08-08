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
          <li class="breadcrumb-item active" aria-current="page">Data</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <h1 class="h2 d-inline vertical-center">My Data</h1>
                <!-- Button Modal -->
                <button type="button" class="btn btn-primary btn-blue mb-3 float-end" data-bs-toggle="modal" data-bs-target="#modal-upload">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
                    <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2z"/>
                  </svg>
                  Upload data
                </button>
              </div>
            </div>
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <!-- Modal Content -->
                <div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="modal-upload" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="h6 modal-title">Data upload</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="uploadForm" action="" class="mt-4">
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="formFile" class="form-label">File (.geojson, .zip shapefile)</label>
                            <input class="form-control" type="file" id="formFile" name="formFile" accept=".json,.geojson,.zip" required>
                          </div>
                          <small id="fileHelp" class="form-text text-muted">The maximum file size is 15MB. Please keep in mind that larger files may take longer to upload.</small>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnUpload" name="btnUpload">Upload</button>
                          <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End of Modal Content -->
                <div class="table-responsive">
                  <table class="table table-striped table-hover" style="width:100%" id="dataTable" name="dataTable">
                    <thead><tr><th>Name</th><th>File format</th><th>Feature type</th><th>Created</th></tr></thead>
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

  <!-- Data JS -->
  <script src="assets/js/data.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" src="vendor/datatables/datatables.min.js"></script>


</body>

</html>
