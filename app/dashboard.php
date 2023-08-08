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
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row mb-4 mb-lg-5">
              <div class="col-12 col-md-12">
                <h1 class="h2 d-inline vertical-center">My Dashboard</h1>
              </div>
            </div>

            <div class="row mb-4 mb-lg-5">

              <h4>Welcome back</h4>

              <div class="row row-cols-1 row-cols-md-6 g-4 pb-5">
                <div class="col">
                  <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Maps</div>
                    <div class="card-body text-dark">
                      <h5 class="card-title">New map</h5>
                      <p class="card-text">Create a new map and share it with the world.</p>
                      <a href="maps" class="btn btn-primary btn-block w-100">New map</a>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Layers</div>
                    <div class="card-body text-dark">
                      <h5 class="card-title">New layer</h5>
                      <p class="card-text">Create a new layer and share it with the world.</p>
                      <a href="layers" class="btn btn-primary btn-block w-100">New layer</a>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Data</div>
                    <div class="card-body text-dark">
                      <h5 class="card-title">New data</h5>
                      <p class="card-text">Create a new data and share it with the world.</p>
                      <a href="data" class="btn btn-primary btn-block w-100">New data</a>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Analysis</div>
                    <div class="card-body text-dark">
                      <h5 class="card-title">Analyze data</h5>
                      <p class="card-text">Understand the data in the context of location</p>
                      <a href="analysis" class="btn btn-primary btn-block w-100">Analysis</a>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Market</div>
                    <div class="card-body text-dark">
                      <h5 class="card-title">Share on market</h5>
                      <p class="card-text">Sharing with other users coming soon!</p>
                      <a href="market" class="btn btn-primary btn-block w-100">Market</a>
                    </div>
                  </div>
                </div>
              </div>


              <div class="row mb-4 mb-lg-5">
                <h4>What's new?</h4>

                <div class="row row-cols-1 row-cols-md-3 g-4">

                  <p>Coming soon...</p>

                  <!-- <div class="col">
                  <div class="card h-100">
                  <img src="https://www.esri.com/content/dam/esrisites/en-us/about/what-is-gis/assets/image-switcher-maps/monitor-change-what-is-gis-image-switcher.jpg" class="card-img-top" height="300px" width="auto" alt="...">
                  <div class="card-body">
                  <h5 class="card-title">Lorem ipsum</h5>
                  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                <div class="card-footer">
                <small class="text-muted">1 day ago</small>
              </div>
            </div>
          </div>
          <div class="col">
          <div class="card h-100">
          <img src="https://cpe.ucdavis.edu/sites/g/files/dgvnsk4886/files/styles/sf_landscape_16x9/public/media/images/GettyImages-1205638055_0.jpg?h=56d0ca2e&itok=aZKnAa_M" class="card-img-top" height="300px" width="auto" alt="...">
          <div class="card-body">
          <h5 class="card-title">Lorem ipsum</h5>
          <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card-footer">
        <small class="text-muted">5 days ago</small>
      </div>
    </div>
  </div>
  <div class="col">
  <div class="card h-100">
  <img src="https://www.arkance-systems.cz/wp-content/uploads/sites/12/2021/06/GIS_reseni_grid_HP.jpg" class="card-img-top" height="300px" width="auto" alt="...">
  <div class="card-body">
  <h5 class="card-title">Lorem ipsum</h5>
  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
</div>
<div class="card-footer">
<small class="text-muted">12 days ago</small>
</div>
</div>
</div> -->

</div>

</div>

<div class="row mb-4 mb-lg-5">
  <h4>Need help?</h4>

  <div class="row row-cols-1 row-cols-md-12 g-4">


    <p class="fs-5">If you need help, please visit our <a href="docs">documentation page</a> or just <a href="../index#contact" target="_blank">contact us</a>.</p>


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

<!-- Simplebar -->
<script src="vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Volt JS -->
<script src="/assets/js/volt.js"></script>

<!-- jQuery JS -->
<script src="vendor/jquery/jquery-3.6.3.min.js"></script>

<!-- Common JS -->
<script src="assets/js/common.js"></script>


</body>

</html>
