$( document ).ready(function() {

  showLoading();

  initMap();

  $("#btnDelete").click(function(){
    deleteData(getUrlParam('id'));
  });

  $("#btnExport").click(function(){
    let export_format = document.getElementById("export-format").value;
    exportData(getUrlParam('id'), export_format);
  });

  $('#layerDataForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let name = document.getElementById("name-data").value;
    let description = document.getElementById("description-data").value;
    let source = getUrlParam('id');
    let layer_type = "data";

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerAdd", layer_type: layer_type, name: name, description: description, source: source},
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-layer-data').modal('hide');
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

});


function initMap() {
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

  getDataView(getUrlParam('id'), map);
}


function getDataView(data_id, map) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "dataView", data_id: data_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);

      if(data_parsed.status == "ERROR") {
        location.href = "data";
      }

      let json = data_parsed.data;

      prepareTable(json.features[0].properties, json);

      let cdate = data_parsed.cdate;
      let cdate_div = document.getElementById('data_info_created');
      cdate_div.innerHTML += cdate;

      let feature_number = data_parsed.count;
      let number_div = document.getElementById('data_info_number');
      number_div.innerHTML += feature_number;

      let feature_type = data_parsed.type;
      let type_div = document.getElementById('data_info_type');
      type_div.innerHTML += feature_type;

      let name = data_parsed.name;
      document.getElementById("data_name").innerHTML = name;

      addLayerToMap(map, name, json);
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}

function deleteData(data_id) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "dataDelete", data_id: data_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      hideLoading();
      if(data_parsed.status == "OK") {
        showNotif(data_parsed.status, data_parsed.message);
        location.href = "data";
      } else {
        showNotif(data_parsed.status, data_parsed.message);
      }
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}

function exportData(data_id, file_format) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "dataExport", data_id: data_id, format: file_format},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      hideLoading();
      if(data_parsed.status == "OK") {

        let data = "text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(data_parsed.data));

        let a = document.createElement('a');
        a.href = 'data:' + data;
        a.download = 'export.geojson';
        a.innerHTML = '<h6>LINK TO DOWNLOAD<h6>';

        let container = document.getElementById('export-output');
        container.appendChild(a);

        showNotif(data_parsed.status, data_parsed.message);
      } else {
        showNotif(data_parsed.status, data_parsed.message);
      }
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}

function prepareTable(json_properties, json) {
  let properties = [];
  for (let property of Object.keys(json_properties)) {
    //if(property != "ms_fid") {
      properties.push({data: "properties."+property, title: property});
    //}
  }

  let features = json.features;

  $('#dataTable').DataTable({
    pageLength: 10,
    lengthChange: false,
    searching: false,
    data: features,
    columns: properties
  });

}

function getUrlParam(param) {
  let queryString = window.location.search;
  let urlParams = new URLSearchParams(queryString);
  return urlParams.get(param);
}

function addLayerToMap(map, name, json) {
  let lname = L.geoJSON(json,
    {
      onEachFeature: function (feature, layer) {
       layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
      }
    }
  ).addTo(map);
  map.fitBounds(lname.getBounds());
  hideLoading();
}
