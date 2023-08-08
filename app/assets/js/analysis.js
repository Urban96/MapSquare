$( document ).ready(function() {

  showLoading();
  getListOfLayers();

  let map = L.map('map').setView([51.505, -0.09], 3);

  let carto = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
  }).addTo(map);

  let osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  });

  let ortophoto = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
  });

  let none = L.tileLayer('', {
    attribution: ''
  });

  let baseMaps = {
    "Carto": carto,
    "OpenStreetMap": osm,
    "Ortophoto": ortophoto,
    "None": none
  };

  let layerControl = L.control.layers(baseMaps).addTo(map);

  function styleIsolines(feature) {
    return {
      color: '#0073d4',
      opacity: 0.5,
      fillOpacity: 0.2
    };
  }

  function highlightIsolines(e) {
    let layer = e.target;

    layer.setStyle({
      fillColor: '#ffea00',
      dashArray: '1,13',
      weight: 4,
      fillOpacity: '0.5',
      opacity: '1'
    });
  }

  function resetIsolines(e) {
    let layer = e.target;
    reachabilityControl.isolinesGroup.resetStyle(layer);
  }

  function clickIsolines(e) {
    let layer = e.target;
    layer.addTo(map);
    let props = layer.feature.properties;
    let popupContent = 'Mode of travel: ' + props['Travel mode'] + '<br />Range: 0 - ' + props['Range'] + ' ' + props['Range units'] + '<br />Area: ' + props['Area'] + ' ' + props['Area units'] + '<br />Population: ' + props['Population'];
    if (props.hasOwnProperty('Reach factor')) popupContent += '<br />Reach factor: ' + props['Reach factor'];
    layer.bindPopup(popupContent).openPopup();
  }

  function isolinesOrigin(latLng, travelMode, rangeType) {
    return L.circleMarker(latLng, { radius: 4, weight: 2, color: '#0073d4', fillColor: '#fff', fillOpacity: 1 });
  }

  let reachabilityControl = L.control.reachability({
    apiKey: '', // PLEASE REGISTER WITH OPENROUTESERVICE FOR YOUR OWN KEY!
    styleFn: styleIsolines,
    mouseOverFn: highlightIsolines,
    mouseOutFn: resetIsolines,
    clickFn: clickIsolines,
    markerFn: isolinesOrigin,
    collapsed: false,
    expandButtonContent: '',
    expandButtonStyleClass: 'reachability-control-expand-button fa fa-bullseye',
    collapseButtonContent: '',
    collapseButtonStyleClass: 'reachability-control-collapse-button fa fa-caret-up',
    drawButtonContent: '',
    drawButtonStyleClass: 'fa fa-pencil',
    deleteButtonContent: '',
    deleteButtonStyleClass: 'fa fa-trash',
    distanceButtonContent: '',
    distanceButtonStyleClass: 'fa fa-road',
    timeButtonContent: '',
    timeButtonStyleClass: 'fa fa-clock-o',
    travelModeButton1Content: '',
    travelModeButton1StyleClass: 'fa fa-car',
    travelModeButton2Content: '',
    travelModeButton2StyleClass: 'fa fa-bicycle',
    travelModeButton3Content: '',
    travelModeButton3StyleClass: 'fa fa-male',
    travelModeButton4Content: '',
    travelModeButton4StyleClass: 'fa fa-wheelchair-alt'
  });

  map.on('reachability:error', function () {
    alert('Unfortunately there has been an error calling the API.\nMore details are available in the console.');
  });

  map.on('reachability:no_data', function () {
    alert('Unfortunately no data was received from the API.\n');
  });

  hideLoading();

  let isochronesActive = false;

  $('#isochronesLink').click(function(e)
  {
    if (reachabilityControl._map == undefined) {
      map.addControl(reachabilityControl);
    } else {
      map.removeControl(reachabilityControl);
    }
  });

  $('#layerForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let name = document.getElementById("name-layer").value;
    let source_layer = document.getElementById("source-layer").value;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerView", layer_id: source_layer},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        let json = data_parsed.data;

        $('#modal-add-layer').modal('hide');
        addLayerToMap(map, name, data_parsed.name, json, data_parsed.count);

        hideLoading();
        showNotif(data_parsed.status, data_parsed.message);
      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
        hideLoading();
      }
    });

  });

  $('#bufferForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let buffer_source = document.getElementById("buffer-source").value;
    let buffer_radius = document.getElementById("buffer-radius").value;
    let buffer_unit = document.getElementById("buffer-unit").value;
    let buffer_steps = parseInt(document.getElementById("buffer-steps").value);

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerView", layer_id: buffer_source},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        let json = data_parsed.data;

        let buffered = turf.buffer(json, buffer_radius, {units: buffer_unit, steps: buffer_steps});
        addLayerToMap(map, "buffer", "buffer", buffered);

        if (document.getElementById('buffer-export').checked) {
          let layer_type = "analysis";

          $.ajax({
            method:"POST",
            url: 'server/router.php',
            data: {act: "layerAdd", layer_type: layer_type, name: "Buffer", description: "Layer executed in analysis", source: JSON.stringify(buffered)},
            success: function(data){
              let data_parsed = jQuery.parseJSON (data);
              if (data_parsed.status == "OK") {
                showNotif(data_parsed.status, data_parsed.message);
              } else {

              }

              $('#modal-buffer').modal('hide');
              hideLoading();
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText);
              console.log(error);
              alert(xhr.responseText);
            }
          });

        } else {
          $('#modal-buffer').modal('hide');
          hideLoading();

          showNotif(data_parsed.status, data_parsed.message);
        }

      },
      error: function(xhr, status, error) {
        $('#modal-buffer').modal('hide');
        alert(xhr.responseText);
        hideLoading();
      }
    });

    hideLoading();
  });




  $('#simplifyForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let simplify_source = document.getElementById("simplify-source").value;
    let simplify_tolerance = document.getElementById("simplify-tolerance").value;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerView", layer_id: simplify_source},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        let json = data_parsed.data;

        if (data_parsed.type != "Polygon") {
          showNotif("ERROR", "Not suitable layer.");
        }

        let simplified = turf.simplify(json, {tolerance: simplify_tolerance, highQuality: true});
        addLayerToMap(map, "simplify", "simplify", simplified);

        if (document.getElementById('simplify-export').checked) {
          let layer_type = "analysis";

          $.ajax({
            method:"POST",
            url: 'server/router.php',
            data: {act: "layerAdd", layer_type: layer_type, name: "Simplify", description: "Layer executed in analysis", source: JSON.stringify(simplified)},
            success: function(data){
              let data_parsed = jQuery.parseJSON (data);
              if (data_parsed.status == "OK") {
                showNotif(data_parsed.status, data_parsed.message);
              } else {

              }

              $('#modal-simplify').modal('hide');
              hideLoading();
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText);
              console.log(error);
              alert(xhr.responseText);
            }
          });

        } else {
          $('#modal-simplify').modal('hide');
          hideLoading();

          showNotif(data_parsed.status, data_parsed.message);
        }

      },
      error: function(xhr, status, error) {
        $('#modal-simplify').modal('hide');
        alert(xhr.responseText);
        hideLoading();
      }
    });

    hideLoading();
  });


  $('#smoothForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let smooth_source = document.getElementById("smooth-source").value;
    let smooth_iterations = document.getElementById("smooth-iterations").value;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerView", layer_id: smooth_source},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        let json = data_parsed.data;

        if (data_parsed.type != "Polygon") {
          showNotif("ERROR", "Not suitable layer.");
        }

        let smooth = turf.polygonSmooth(json, {iterations: smooth_iterations});
        addLayerToMap(map, "smooth", "smooth", smooth);

        if (document.getElementById('smooth-export').checked) {
          let layer_type = "analysis";

          $.ajax({
            method:"POST",
            url: 'server/router.php',
            data: {act: "layerAdd", layer_type: layer_type, name: "Smooth", description: "Layer executed in analysis", source: JSON.stringify(smooth)},
            success: function(data){
              let data_parsed = jQuery.parseJSON (data);
              if (data_parsed.status == "OK") {
                showNotif(data_parsed.status, data_parsed.message);
              } else {

              }

              $('#modal-smooth').modal('hide');
              hideLoading();
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText);
              console.log(error);
              alert(xhr.responseText);
            }
          });

        } else {
          $('#modal-smooth').modal('hide');
          hideLoading();

          showNotif(data_parsed.status, data_parsed.message);
        }

      },
      error: function(xhr, status, error) {
        $('#modal-smooth').modal('hide');
        alert(xhr.responseText);
        hideLoading();
      }
    });

    hideLoading();
  });


  $('#voronoiForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let voronoi_source = document.getElementById("voronoi-source").value;
    let voronoi_bbox = document.getElementById("voronoi-bbox").value;

    console.log(voronoi_bbox);

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerView", layer_id: voronoi_source},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        let json = data_parsed.data;


        if (data_parsed.type != "Point") {
          showNotif("ERROR", "Not suitable layer.");
        }

        let options = {};
        switch (voronoi_bbox) {
          case "layerbbox":
            let bbox = turf.bbox(json);
            options = {bbox: bbox};
            break;
          default:
            options = {};
        }

        let voronoi = turf.voronoi(json, options);
        if (voronoi.features.length == 1) {
          voronoi = turf.featureCollection([voronoi]);
        }
        addLayerToMap(map, "voronoi", "voronoi", voronoi);

        if (document.getElementById('voronoi-export').checked) {
          let layer_type = "analysis";

          $.ajax({
            method:"POST",
            url: 'server/router.php',
            data: {act: "layerAdd", layer_type: layer_type, name: "Voronoi", description: "Layer executed in analysis", source: JSON.stringify(voronoi)},
            success: function(data){
              let data_parsed = jQuery.parseJSON (data);
              if (data_parsed.status == "OK") {
                showNotif(data_parsed.status, data_parsed.message);
              } else {

              }

              $('#modal-voronoi').modal('hide');
              hideLoading();
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText);
              console.log(error);
              alert(xhr.responseText);
            }
          });

        } else {
          $('#modal-voronoi').modal('hide');
          hideLoading();

          showNotif(data_parsed.status, data_parsed.message);
        }

      },
      error: function(xhr, status, error) {
        $('#modal-voronoi').modal('hide');
        alert(xhr.responseText);
        hideLoading();
      }
    });

    hideLoading();
  });

});

function getListOfLayers() {

  showLoading();

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "layers"},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      populateLayerSelect("source-layer", data_parsed.data);

      populateLayerSelect("buffer-source", data_parsed.data);
      populateLayerSelect("simplify-source", data_parsed.data, ["Polygon"]);
      populateLayerSelect("smooth-source", data_parsed.data, ["Polygon"]);
      populateLayerSelect("voronoi-source", data_parsed.data, ["Point"]);
      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}

function addLayerToMap(map, name, source_name, json, count) {
  let layerProperties = [];
  if (count != 0) {
    for (let property of Object.keys(json.features[0].properties)) {
      layerProperties.push(property);
    }
  }

  let layer = L.geoJSON(json,
    {
      onEachFeature: function (feature, layer) {
        layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
      }
    }
  ).addTo(map);

  if (count != 0) {
    map.fitBounds(layer.getBounds());
  }
}
