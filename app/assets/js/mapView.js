$(document).ready(function() {
  showLoading();

  getListOfLayers();

  initMapInfo(getUrlParam('id'));
  getMapLayers(getUrlParam('id'));


  $('#layerForm').on('submit', function(e) {
    e.preventDefault();

    $('#modal-layer').modal('hide');

    let layer_id = document.getElementById("source-layer").value;
    let layer_name = document.getElementById("source-layer").options[document.getElementById("source-layer").selectedIndex].text;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "mapInsertLayer", id: getUrlParam('id'), layer_id: layer_id},
      success: function(data){

        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {

          let layer = document.createElement("button");
          layer.className = "list-group-item list-group-item-action";
          layer.name = "mapLayer";
          layer.id = layer_id;
          layer.value = layer_id;
          layer.innerHTML = layer_name;
          document.getElementById("layerList").appendChild(layer);
          layer.addEventListener('click', function() {
              deleteLayer(layer.value)
          }, false);

          $('#modal-layer').modal('hide');
        } else {

        }

        hideLoading();
        showNotif(data_parsed.status, data_parsed.message);
      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
      }
    });

  });

  $('#saveChanges').on('click', function(e) {

    let name = document.getElementById("name").value;
    let template = document.getElementById("template").value;
    let color = document.getElementById("color").value;
    let minZoom = document.getElementById("minZoom").value;
    let maxZoom = document.getElementById("maxZoom").value;
    let fitBounds = document.getElementById("fitBounds").checked ? 1 : 0;

    let basemap_carto = document.getElementById("basemap_carto").checked ? 1 : 0;
    let basemap_osm_basic = document.getElementById("basemap_osm_basic").checked ? 1 : 0;
    let basemap_osm_mapnik = document.getElementById("basemap_osm_mapnik").checked ? 1 : 0;
    let basemap_cycloosm = document.getElementById("basemap_cycloosm").checked ? 1 : 0;
    let basemap_esri_ortophoto = document.getElementById("basemap_esri_ortophoto").checked ? 1 : 0;
    let basemap_none = document.getElementById("basemap_none").checked ? 1 : 0;


    let plugin_geolocate = document.getElementById("plugin_geolocate").checked ? 1 : 0;
    let plugin_fullscreen = document.getElementById("plugin_fullscreen").checked ? 1 : 0;
    let plugin_print = document.getElementById("plugin_print").checked ? 1 : 0;
    let plugin_measurement = document.getElementById("plugin_measurement").checked ? 1 : 0;
    let plugin_minimap = document.getElementById("plugin_minimap").checked ? 1 : 0;
    let plugin_mouse_coords = document.getElementById("plugin_mouse_coords").checked ? 1 : 0;

    let basemaps = {carto: basemap_carto, osm_basic: basemap_osm_basic, osm_mapnik: basemap_osm_mapnik, cycloosm: basemap_cycloosm, esri_ortophoto: basemap_esri_ortophoto, none: basemap_none};
    let plugins = {geolocate: plugin_geolocate, fullscreen: plugin_fullscreen, print: plugin_print, measurement: plugin_measurement, minimap: plugin_minimap, mouse_coords: plugin_mouse_coords};


    let options = {color: color, minZoom: minZoom, maxZoom: maxZoom, fitBounds: fitBounds, basemaps: basemaps, plugins: plugins};


    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "mapUpdate", id: getUrlParam('id'), name: name, template: template, options: options},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);

        showNotif(data_parsed.status, data_parsed.message);
        hideLoading();
      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
        hideLoading();
      }
    });

  });


});


function initMapInfo(map_id) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "mapView", id: map_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);

      if(data_parsed.status == "ERROR") {
        location.href = "maps";
      }

      document.getElementById("mapUrl").href = "../s/map?id=" + map_id;

      document.getElementById("name").value = data_parsed.name;
      document.getElementById('template').value = data_parsed.template;
      document.getElementById('color').value = data_parsed.options.color;

      if (data_parsed.options.fitBounds == 1) {
        document.getElementById("fitBounds").checked = true;
      }

      document.getElementById("minZoom").value = data_parsed.options.minZoom;
      document.getElementById("maxZoom").value = data_parsed.options.maxZoom;

      let basemaps = data_parsed.options.basemaps;
      for (let key in basemaps) {
          if (basemaps.hasOwnProperty(key)) {
            if (basemaps[key] == 1) {
              document.getElementById("basemap_" + key).checked = true;
            }
          }
      }

      let plugins = data_parsed.options.plugins;
      for (let key in plugins) {
          if (plugins.hasOwnProperty(key)) {
            if (plugins[key] == 1) {
              document.getElementById("plugin_" + key).checked = true;
            }
          }
      }

    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}


function getListOfLayers() {

  showLoading();

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "layers"},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      populateLayerSelect("source-layer", data_parsed.data);


      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}


function getMapLayers(id) {

  showLoading();

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "mapLayers", id: id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      let arrayLength = data_parsed.data.length;
      for (let i = 0; i < arrayLength; i++) {
          let layer = document.createElement("button");
          layer.className = "list-group-item list-group-item-action";
          layer.name = "mapLayer";
          layer.id = data_parsed.data[i].layer_id;
          layer.value = data_parsed.data[i].layer_id;
          layer.innerHTML = data_parsed.data[i].name;
          document.getElementById("layerList").appendChild(layer);

          layer.addEventListener('click', function() {
              deleteLayer(layer.value)
          }, false);
      }

      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}


function deleteLayer(layer_id) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "mapLayerDelete", id: getUrlParam('id'), layer_id: layer_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      document.getElementById(layer_id).remove();

      showNotif(data_parsed.status, data_parsed.message);
      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}
