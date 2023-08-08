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
          <li class="breadcrumb-item active" aria-current="page">Documentation</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <h1 class="h2 d-inline vertical-center">Documentation</h1>
              </div>
            </div>


            <div class="row mb-4 mb-lg-5">
              <div class="col-lg-4 col-sm-6">
                <h2 class="h5 mb-3 pt-3">Information</h2>
                <p><strong>Version:</strong> 1.0.02</p>
                <p><strong>Release date:</strong> 27.6.2023</p>
                <p><strong>License:</strong> <a href="LICENSE.md" target="_blank">Click here</a></p>
                <p><strong>Contributors, special thanks to:</strong> Dept. of Geoinformatics, OpenStreetMap contributors, OpenRouteService, UIDeck, Themesberg, Leaflet,
                  DataTables, Bootstrap, php-shapefile and many more ...</p>

                </div>
                <div class="col-lg-4 col-sm-6">
                  <h2 class="h5 mb-3 pt-3">Docs and manual</h2>
                  <p>Coming soon...</p>
                </div>
                <div class="col-lg-4 col-sm-6">
                  <h2 class="h5 mb-3 pt-3">Updates</h2>
                  <h6>8.8.2023</h6>
                  <ul>
                    <li>Fixed several bugs</li>
                  </ul>
                  <h6>17.7.2023</h6>
                  <ul>
                    <li>Fixed several bugs when uploading a file</li>
                    <li>Added legend</li>
                  </ul>
                  <h6>27.6.2023</h6>
                  <ul>
                    <li>Released version 1.0.00</li>
                    <li>Functions Data, Layers, Analysis, Maps</li>
                    <li>User system</li>
                  </ul>
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

      <!-- Notyf -->
      <script src="vendor/notyf/notyf.min.js"></script>

      <!-- Volt JS -->
      <script src="/assets/js/volt.js"></script>

      <!-- jQuery JS -->
      <script src="vendor/jquery/jquery-3.6.3.min.js"></script>

      <!-- Common JS -->
      <script src="assets/js/common.js"></script>

    </body>

    </html>
