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

  <!-- Leaflet CSS -->
  <link rel="stylesheet" type="text/css" href="vendor/leaflet/leaflet.css"/>


</head>

<body>

  <div id="loadingScreen" class="loadingScreen">
    <div class="spinner-border"></div>
  </div>

  <div id="menuDiv"></div>

  <main class="content">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
      <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
          <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
              <a href="#">
                <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
              </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Data</li>
          </ol>
        </nav>

      </div>
      <div class="btn-toolbar mb-2 mb-md-0 align-items-center d-block">
        <p class="d-inline fw-lighter pe-5" id="data_info_created" name="data_info_created"><strong>Created: </strong></p>
        <p class="d-inline fw-lighter pe-5" id="data_info_number" name="data_info_number"><strong>Number of features: </strong></p>
        <p class="d-inline fw-lighter pe-5" id="data_info_type" name="data_info_type"><strong>Type: </strong></p>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" id="data_name" name="data_name" id="dropdownMenuReference" data-bs-toggle="dropdown"></button>
          <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            <span class="visually-hidden">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu py-0" aria-labelledby="dropdownMenuReference">
            <li><a class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-layer-data">Create layer</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-attribute-data">Show attributes</a></li>
            <li><a class="dropdown-item" href="" disabled>Share on market</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-export-data">Export data</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item rounded-bottom text-red" data-bs-toggle="modal" data-bs-target="#modal-delete-data">Delete</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body p-0">

            <!-- Modal Content -->
            <div class="modal fade" id="modal-layer-data" tabindex="-1" role="dialog" aria-labelledby="modal-layer-data" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h2 class="h6 modal-title">Create layer</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="layerDataForm" action="" class="mt-4">
                    <div class="modal-body">
                      <div class="mb-4">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name-data" name="name-data" aria-describedby="Name" placeholder="Enter the name of the layer" required>
                      </div>
                      <div class="my-4">
                        <label for="textarea">Description <small>(optional)</small></label>
                        <textarea class="form-control" id="description-data" name="description-data" placeholder="Enter the description..." rows="2"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnCreate" name="btnCreate">Create new layer</button>
                      <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal Content -->

            <!-- Modal Content -->
            <div class="modal fade" id="modal-delete-data" tabindex="-1" role="dialog" aria-labelledby="modal-delete-data" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h2 class="h6 modal-title">Delete data</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="" action="" class="mt-4">
                    <div class="modal-body">
                      <p>Are you sure? You are about to delete your data, this action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary btn-blue mb-3" id="btnDelete" name="btnDelete">Yes, delete</button>
                      <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Modal Content -->

            <!-- Modal Content -->
            <div class="modal fade" id="modal-attribute-data" tabindex="-1" role="dialog" aria-labelledby="modal-attribute-data" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h2 class="h6 modal-title">Attribute table</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="" action="" class="mt-4">
                    <div class="modal-body">
                      <div class="table-responsive">
                        <table class="table table-striped table-hover" style="width:100%" id="dataTable" name="dataTable">
                        </table>
                      </div>
                    </div>
                    <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                  </div> -->
                </form>
              </div>
            </div>
          </div>
          <!-- End of Modal Content -->

          <!-- Modal Content -->
          <div class="modal fade" id="modal-export-data" tabindex="-1" role="dialog" aria-labelledby="modal-export-data" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="h6 modal-title">Export data</h2>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataExportForm" action="" class="mt-4">
                  <div class="modal-body">
                    <div class="mb-4">
                      <label class="my-1 me-2" for="type-new">File format</label>
                      <select class="form-select" id="export-format" name="export-format" aria-label="File format" required>
                        <option selected disabled value="">Please select one of these file formats</option>
                        <option value="geojson">GeoJSON</option>
                      </select>
                    </div>
                    <!-- End of Form -->
                    <div id="export-output"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-blue mb-3" id="btnExport" name="btnExport">Export</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End of Modal Content -->


          <div id="map" class="map"></div>

          <!-- </div>
        </div> -->
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

<!-- Simplebar -->
<script src="vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Volt JS -->
<script src="assets/js/volt.js"></script>

<!-- DataTables JS -->
<script src="vendor/leaflet/leaflet.js"></script>

<!-- jQuery JS -->
<script src="vendor/jquery/jquery-3.6.3.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" src="vendor/datatables/datatables.min.js"></script>

<!-- Common JS -->
<script src="assets/js/common.js"></script>

<!-- Data View JS -->
<script src="assets/js/dataView.js"></script>





</body>

</html>
