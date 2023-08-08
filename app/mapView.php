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
              <h1 class="h2 d-inline vertical-center">Map</h1>
              <a href="" id="mapUrl" target="_blank">
                <button type="button" class="btn btn-primary btn-blue mb-3 ms-2 float-end" id="viewMap" name="viewMap">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM3.668 2.501l-.288.646a.847.847 0 0 0 1.479.815l.245-.368a.809.809 0 0 1 1.034-.275.809.809 0 0 0 .724 0l.261-.13a1 1 0 0 1 .775-.05l.984.34c.078.028.16.044.243.054.784.093.855.377.694.801-.155.41-.616.617-1.035.487l-.01-.003C8.274 4.663 7.748 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 1.96.826 2.166 1.696 2.382.46.115.935.233 1.304.618.449.467.393 1.181.339 1.877C6.755 12.96 6.674 14 8.5 14c1.75 0 3-3.5 3-4.5 0-.262.208-.468.444-.7.396-.392.87-.86.556-1.8-.097-.291-.396-.568-.641-.756-.174-.133-.207-.396-.052-.551a.333.333 0 0 1 .42-.042l1.085.724c.11.072.255.058.348-.035.15-.15.415-.083.489.117.16.43.445 1.05.849 1.357L15 8A7 7 0 1 1 3.668 2.501Z"/>                </svg>
                    View map
                  </button>
                </a>
                <button type="button" class="btn btn-primary btn-blue mb-3 float-end" id="saveChanges" name="saveChanges">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
                    <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                  </svg>
                  Save changes
                </button>
              </div>
            </div>


            <div class="row">
              <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                  <div class="card-body">
                    <div class="row mb-4">
                      <div class="col-lg-4 col-sm-6">
                        <!-- Form -->
                        <div class="mb-5">
                          <label for="name">Map name</label>
                          <input type="text" class="form-control" id="name">
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="mb-3">
                          <label class="my-1 me-2" for="template">Template</label>
                          <select class="form-select" id="template" aria-label="Template">
                            <option value="0_basic" selected>Basic</option>
                            <option value="1_sidebar" disabled>Left sidebar</option>
                          </select>
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="mb-5">
                          <label class="my-1 me-2" for="color">Color schema</label>
                          <select class="form-select" id="color" aria-label="Color">
                            <option selected disabled>Please select on of these options</option>
                            <option value="green" disabled>Green</option>
                            <option value="blue">Blue</option>
                            <option value="red" disabled>Red</option>
                          </select>
                        </div>
                        <!-- Form -->
                        <div class="mb-3">
                          <label for="minZoom">Minimal zoom</label>
                          <input type="number" class="form-control" id="minZoom" aria-describedby="minZoom" value="" min="2" max="20">
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="mb-5">
                          <label for="maxZoom">Maximal zoom</label>
                          <input type="number" class="form-control" id="maxZoom" aria-describedby="maxZoom" value="" min="2" max="20">
                        </div>
                        <!-- End of Form -->
                        <div class="mb-3">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="fitBounds">
                            <label class="form-check-label" for="fitBounds">Fit bounds on startup</label>
                          </div>
                        </div>
                        <!-- End of Form -->
                      </div>

                      <div class="col-lg-4 col-sm-6">

                        <!-- Form -->
                        <div class="mb-3">
                          <label for="basemap">Base maps</label>
                        </div>
                        <!-- End of Form -->
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_carto">
                            <label class="form-check-label" for="basemap_carto">Carto (black and white)</label>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_osm_basic">
                            <label class="form-check-label" for="basemap_osm_basic">OpenStreetMap Basic</label>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_osm_mapnik" disabled>
                            <label class="form-check-label" for="basemap_osm_mapnik">OpenStreetMap Mapnik</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_cycloosm" disabled>
                            <label class="form-check-label" for="basemap_cycloosm">CycloOSM</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_esri_ortophoto">
                            <label class="form-check-label" for="basemap_esri_ortophoto">ESRI Ortophoto</label>
                          </div>
                        </div>
                        <div class="mb-5">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="basemap_none" disabled>
                            <label class="form-check-label" for="basemap_none">None</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>

                        <!-- Form -->
                        <div class="mb-3">
                          <label class="my-1 me-2" for="layers">Layers</label>
                          <button type="button" class="btn btn-primary btn-blue mb-3 float-end" data-bs-toggle="modal" data-bs-target="#modal-layer">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add layer
                          </button>

                        </div>

                        <div>
                          <small>To delete a layer, click on it</small>
                          <div class="list-group pt-3" id="layerList" name="layerList"></div>
                        </div>

                        <!-- Form -->

                      </div>

                      <div class="col-lg-4 col-sm-6">

                        <!-- Form -->
                        <div class="mb-3">
                          <label for="plugins">Plugins</label>
                        </div>
                        <!-- End of Form -->
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_geolocate">
                            <label class="form-check-label" for="plugin_geolocate">Geolocation plugin</label>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_fullscreen">
                            <label class="form-check-label" for="plugin_fulscreen">Fullscreen plugin</label>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_print" disabled>
                            <label class="form-check-label" for="plugin_print">Print plugin</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_measurement" disabled>
                            <label class="form-check-label" for="plugin_measurement">Measurement plugin</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_minimap" disabled>
                            <label class="form-check-label" for="plugin_minimap">Synced minimap plugin</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>
                        <div class="mb-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="plugin_mouse_coords" disabled>
                            <label class="form-check-label" for="plugin_mouse_coords">Mouse coordinates plugin</label>
                            <span>
                              <span class="badge badge-sm bg-secondary ms-1 text-gray-800">SOON</span>
                            </span>
                          </div>
                        </div>

                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>



          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-layer" tabindex="-1" role="dialog" aria-labelledby="modal-layer" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="h6 modal-title">Add a layer to the map</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="layerForm" action="" class="mt-4">
            <div class="modal-body">

              <!-- Form -->
              <div class="mb-4">
                <label class="my-1 me-2" for="add-layer">Layer</label>
                <select class="form-select" id="source-layer" name="source-layer" aria-label="Layer" required>
                  <option selected disabled value="">Please select one of these layers</option>
                </select>
              </div>
              <!-- End of Form -->
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-blue mb-3" id="addLayer" name="addLayer">Add layer</button>
              <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End of Modal Content -->


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

  <!-- Map View JS -->
  <script src="assets/js/mapView.js"></script>


</body>

</html>
