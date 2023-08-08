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
  <link rel="stylesheet" type="text/css" href="vendor/DataTables/datatables.min.css"/>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" type="text/css" href="vendor/leaflet/leaflet.css"/>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/trafforddatalab/leaflet.reachability@v2.0.1/leaflet.reachability.min.css"/>


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
              <a href="dashboard">
                <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
              </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Analysis</li>
          </ol>
        </nav>

      </div>
      <div class="btn-toolbar mb-2 mb-md-0 align-items-center d-block">

        <button type="button" class="btn btn-primary" id="data_name" name="data_name" id="dropdownMenuReference" data-bs-toggle="modal" data-bs-target="#modal-add-layer">Add layer</button>


        <!-- <div class="btn-group">
        <button type="button" class="btn btn-primary" id="data_name" name="data_name" id="dropdownMenuReference" data-bs-toggle="dropdown">Layers</button>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="visually-hidden">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu py-0"  id="layer_list" name="layer_list" aria-labelledby="dropdownMenuReference">
      <li><a class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-add-layer">Add layer</a></li>
      <li><hr class="dropdown-divider"></li>
    </ul>
  </div> -->

  <div class="btn-group">
    <button type="button" class="btn btn-primary" id="data_name" name="data_name" id="dropdownMenuReference" data-bs-toggle="dropdown">Spatial tools</button>
    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
      <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
      <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu py-0" aria-labelledby="dropdownMenuReference">
      <li><a id="bufferLink" class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-buffer">Buffer</a></li>
      <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-attribute-data" id="isochronesLink">Isochrones</a></li>
      <li><a id="simplifyLink" class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-simplify">Simplify polygon</a></li>
      <li><a id="smoothLink" class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-smooth">Smooth polygon</a></li>
      <li><a id="voronoiLink" class="dropdown-item rounded-top" data-bs-toggle="modal" data-bs-target="#modal-voronoi">Voronoi polygons</a></li>
    </ul>
  </div>
</div>
</div>

<div class="row">
  <div class="col-12 mb-4">
    <div class="card border-0 shadow components-section">
      <div class="card-body p-0">

        <!-- Modal Content -->
        <div class="modal fade" id="modal-add-layer" tabindex="-1" role="dialog" aria-labelledby="modal-add-layer" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="h6 modal-title">Add layer</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="layerForm" action="" class="mt-4">
                <div class="modal-body">
                  <div class="mb-4">
                    <label for="name-layer">Name</label>
                    <input type="text" class="form-control" id="name-layer" name="name-layer" aria-describedby="Name" placeholder="Enter the name of the layer" required>
                  </div>
                  <div class="mb-4">
                    <label class="my-1 me-2" for="source-layer">Layer source</label>
                    <select class="form-select" id="source-layer" name="source-layer" aria-label="Layer source" required>
                      <option selected disabled value="">Please select one of your layer sources</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of your layer sources.</small>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-blue mb-3" id="btnAddLayer" name="btnAddLayer">Add layer</button>
                  <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal Content -->

        <!-- Modal Content -->
        <div class="modal fade" id="modal-buffer" name="modal-buffer" tabindex="-1" role="dialog" aria-labelledby="modal-buffer" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="h6 modal-title">Buffer</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="bufferForm" action="" class="mt-4">
                <div class="modal-body">
                  <div class="mb-4">
                    <label class="my-1 me-2" for="buffer-source">Layer source</label>
                    <select class="form-select" id="buffer-source" name="buffer-source" aria-label="Buffer source" required>
                      <option selected disabled value="">Please select one of your layer sources</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of your layer sources.</small>
                  </div>
                  <div class="mb-4">
                    <label for="buffer-radius">Radius</label>
                    <input type="number" class="form-control" id="buffer-radius" name="buffer-radius" min="0" max="10000" step="1" aria-describedby="Buffer Radius" value="1" required>
                  </div>
                  <div class="mb-4">
                    <label for="name-layer">Unit</label>
                    <select class="form-select" id="buffer-unit" name="buffer-unit" aria-label="Buffer unit" required>
                      <option value="kilometers" selected>Kilometers</option>
                      <option value="miles">Miles</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="buffer-steps">Steps</label>
                    <input type="number" class="form-control" id="buffer-steps" name="buffer-steps" min="8" max="128" step="1" aria-describedby="Buffer Steps" value="32" required>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="buffer-export">Export to layers</label>
                    <input class="form-check-input" type="checkbox" value="" id="buffer-export" name="buffer-export">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-blue mb-3" id="executeBuffer" name="executeBuffer">Execute</button>
                  <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal Content -->

        <!-- Modal Content -->
        <div class="modal fade" id="modal-simplify" name="modal-simplify" tabindex="-1" role="dialog" aria-labelledby="modal-simplify" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="h6 modal-title">Simplify polygon</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="simplifyForm" action="" class="mt-4">
                <div class="modal-body">
                  <div class="mb-4">
                    <label class="my-1 me-2" for="simplify-source">Layer source</label>
                    <select class="form-select" id="simplify-source" name="simplify-source" aria-label="Simplify source" required>
                      <option selected disabled value="">Please select one of your layer sources</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of your layer sources.</small>
                  </div>
                  <div class="mb-4">
                    <label for="simplify-tolerance">Tolerance</label>
                    <input type="number" class="form-control" id="simplify-tolerance" name="simplify-tolerance" min="0.01" max="10" step="0.01" aria-describedby="Tolerance" value="1" required>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="simplify-export">Export to layers</label>
                    <input class="form-check-input" type="checkbox" value="" id="simplify-export" name="simplify-export">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-blue mb-3" id="executeSimplify" name="executeSimplify">Execute</button>
                  <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal Content -->


        <!-- Modal Content -->
        <div class="modal fade" id="modal-smooth" name="modal-smooth" tabindex="-1" role="dialog" aria-labelledby="modal-smooth" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="h6 modal-title">Smooth polygon</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="smoothForm" action="" class="mt-4">
                <div class="modal-body">
                  <div class="mb-4">
                    <label class="my-1 me-2" for="smooth-source">Layer source</label>
                    <select class="form-select" id="smooth-source" name="smooth-source" aria-label="Smooth source" required>
                      <option selected disabled value="">Please select one of your layer sources</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of your layer sources.</small>
                  </div>
                  <div class="mb-4">
                    <label for="smooth-tolerance">Iterations</label>
                    <input type="number" class="form-control" id="smooth-iterations" name="smooth-iterations" min="1" max="10" step="1" aria-describedby="Iterations" value="3" required>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="smooth-export">Export to layers</label>
                    <input class="form-check-input" type="checkbox" value="" id="smooth-export" name="smooth-export">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-blue mb-3" id="executeSmooth" name="executeSmooth">Execute</button>
                  <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal Content -->



        <!-- Modal Content -->
        <div class="modal fade" id="modal-voronoi" name="modal-voronoi" tabindex="-1" role="dialog" aria-labelledby="modal-smvoronoiooth" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="h6 modal-title">Voronoi polygons</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="voronoiForm" action="" class="mt-4">
                <div class="modal-body">
                  <div class="mb-4">
                    <label class="my-1 me-2" for="voronoi-source">Layer source</label>
                    <select class="form-select" id="voronoi-source" name="voronoi-source" aria-label="Voronoi source" required>
                      <option selected disabled value="">Please select one of your layer sources</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of your layer sources.</small>
                  </div>
                  <div class="mb-4">
                    <label class="my-1 me-2" for="voronoi-bbox">Bounding box</label>
                    <select class="form-select" id="voronoi-bbox" name="voronoi-bbox" aria-label="Voronoi bbox" required>
                      <option selected disabled value="">Please select one of these options</option>
                      <option value="layerbbox">Layer's extent</option>
                      <!-- <option value="currentbbox">Current extent</option> -->
                      <option value="worldbbox">Whole world</option>
                    </select>
                    <small id="typeHelp" class="form-text text-muted">Please select one of these options.</small>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="voronoi-export">Export to layers</label>
                    <input class="form-check-input" type="checkbox" value="" id="voronoi-export" name="voronoi-export">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-blue mb-3" id="executeVoronoi" name="executeVoronoi">Execute</button>
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

<!-- Volt JS -->
<script src="assets/js/volt.js"></script>

<!-- DataTables JS -->
<script src="vendor/leaflet/leaflet.js"></script>

<!-- jQuery JS -->
<script src="vendor/jquery/jquery-3.6.3.min.js"></script>

<!-- Common JS -->
<script src="assets/js/common.js"></script>

<!-- Analysis JS -->
<script src="assets/js/analysis.js"></script>

<!-- Turf JS -->
<script src="vendor/turf/turf.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/trafforddatalab/leaflet.reachability@v2.0.1/leaflet.reachability.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>



</body>

</html>
