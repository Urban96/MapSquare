$( document ).ready(function() {

  showLoading();

  initMap();

  $("#exportLayer").click(function(){
    exportLayer(getUrlParam('id'));
  });

  $("#btnDelete").click(function(){
    deleteLayer(getUrlParam('id'));
  });

  $("#layerInfoUpdate").click(function(){

    showLoading();

    let layer_id = getUrlParam('id');

    let layerInfoName = document.getElementById("layer-modal-name").value;
    let layerInfodescription = document.getElementById("layer-modal-description").value;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerUpdate", layer_id: layer_id, name: layerInfoName, description: layerInfodescription},
      success: function(data){

        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          document.getElementById("layer-name").innerHTML = layerInfoName;
          document.getElementById("layer-modal-name").value = layerInfoName;

          document.getElementById("layer-modal-description").value = layerInfodescription;
          $('#modal-layer-info').modal('hide');
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

  $("#layerUpdateSymbology").click(function(){

    showLoading();

    let featureType = document.getElementById("feature-type").value;
    let type = document.getElementById("symbology-type").value;
    let layer_id = getUrlParam('id');

    let style, color, opacity, weight, fillColor, fillOpacity, radius;

    if (featureType == "Point") {

      radius = document.getElementById("symbology-radius").value;

      fillColor = document.getElementById("symbology-fillColor").value;
      fillOpacity = document.getElementById("symbology-fillOpacity").value;

      color = document.getElementById("symbology-color").value;
      opacity = document.getElementById("symbology-opacity").value;
      weight = document.getElementById("symbology-weight").value;

      style = { radius, fillColor, fillOpacity, color, opacity, weight };

    } else if (featureType == "PolyLine") {

      color = document.getElementById("symbology-color").value;
      opacity = document.getElementById("symbology-opacity").value;
      weight = document.getElementById("symbology-weight").value;

      style = {color, opacity, weight };

    } else if (featureType == "Polygon") {

      fillColor = document.getElementById("symbology-fillColor").value;
      fillOpacity = document.getElementById("symbology-fillOpacity").value;

      color = document.getElementById("symbology-color").value;
      opacity = document.getElementById("symbology-opacity").value;
      weight = document.getElementById("symbology-weight").value;

      style = { fillColor, fillOpacity, color, opacity, weight };
    }


    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerSymbology", layer_id: layer_id, type: type, style: style},
      success: function(data){

        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-layer-symbology').modal('hide');

          //Tohle prepracovat, jen updatovat styl existujici vrstvy, nereloadovat celou stranku!
          location.href = "layerView?id=" + layer_id;
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


  getLayerView(getUrlParam('id'), map);
}


function getLayerView(layer_id, map) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: 'layerView', layer_id: layer_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);

      if(data_parsed.status == "ERROR") {
        location.href = "layers";
      }

      let json = data_parsed.data;

      let layer_style = JSON.parse(data_parsed.style);

      if(data_parsed.count != 0) {
        prepareTable(json.features[0].properties, json);
      }

      populateLayerInfo(data_parsed.cdate, data_parsed.count, data_parsed.type, data_parsed.name, data_parsed.description);

      populateLayerSymbology(data_parsed.type, layer_style);

      addLayerToMap(map, data_parsed.name, data_parsed.type, json, layer_style, data_parsed.count);
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
      hideLoading();
    }
  });
}

function exportLayer(layer_id) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "layerExport", layer_id: layer_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      hideLoading();
      if(data_parsed.status == "OK") {
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

function deleteLayer(layer_id) {
  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "layerDelete", layer_id: layer_id},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      hideLoading();
      if(data_parsed.status == "OK") {
        showNotif(data_parsed.status, data_parsed.message);
        location.href = "layers";
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

function populateLayerInfo(cdate, feature_number, feature_type, name, description) {
  let cdate_div = document.getElementById('layer-info-created');
  cdate_div.innerHTML += cdate;

  let number_div = document.getElementById('layer-info-number');
  number_div.innerHTML += feature_number;

  let type_div = document.getElementById('layer-info-type');
  type_div.innerHTML += feature_type;

  document.getElementById("layer-name").innerHTML = name;
  document.getElementById("layer-modal-name").value = name;

  document.getElementById("layer-modal-description").value = description;
}

function populateLayerSymbology(feature_type, style) {

  $(function(){
    if (feature_type == "Point") {
      $('#modal-symbology-body').load('assets/partials/symbologyPoint.html', function() {
        document.getElementById("feature-type").value = feature_type;

        switch(style.type) {
          case "singleSymbol":

            document.getElementById("symbology-type").value = style.type;

            document.getElementById("singleSymbolDetail").style.display = "block";

            document.getElementById("symbology-radius").value = style.style.radius;

            document.getElementById("symbology-fillColor").value = style.style.fillColor;
            document.getElementById("symbology-fillOpacity").value = style.style.fillOpacity;

            document.getElementById("symbology-color").value = style.style.color;
            document.getElementById("symbology-opacity").value = style.style.opacity;
            document.getElementById("symbology-weight").value = style.style.weight;

            document.getElementById("symbology-fillOpacity-Output").value = style.style.fillOpacity;
            document.getElementById("symbology-opacity-Output").value = style.style.opacity;

            break;

        }
      });

    } else if (feature_type == "PolyLine") {
      $('#modal-symbology-body').load('assets/partials/symbologyPolyLine.html', function() {
        document.getElementById("feature-type").value = feature_type;

        switch(style.type) {
          case "singleSymbol":

            document.getElementById("symbology-type").value = style.type;

            document.getElementById("singleSymbolDetail").style.display = "block";

            document.getElementById("symbology-color").value = style.style.color;
            document.getElementById("symbology-opacity").value = style.style.opacity;
            document.getElementById("symbology-weight").value = style.style.weight;

            document.getElementById("symbology-opacity-Output").value = style.style.opacity;

            break;

        }
      });
    } else if (feature_type == "Polygon") {
      $('#modal-symbology-body').load('assets/partials/symbologyPolygon.html', function() {
        document.getElementById("feature-type").value = feature_type;

        $('#symbology-type').on('change', function() {
          //DODELAT
          switch(this.value) {
            case "singleSymbol":
            document.getElementById("singleSymbolDetail").style.display = "block";
              document.getElementById("choroplethDetail").style.display = "none";
              break;
            case "choropleth":
              document.getElementById("choroplethDetail").style.display = "block";
              document.getElementById("singleSymbolDetail").style.display = "none";
              break;
          }
        });

        switch(style.type) {
          case "singleSymbol":
            document.getElementById("symbology-type").value = style.type;

            document.getElementById("singleSymbolDetail").style.display = "block";
            document.getElementById("choroplethDetail").style.display = "none";

            document.getElementById("symbology-fillColor").value = style.style.fillColor;
            document.getElementById("symbology-fillOpacity").value = style.style.fillOpacity;

            document.getElementById("symbology-color").value = style.style.color;
            document.getElementById("symbology-opacity").value = style.style.opacity;
            document.getElementById("symbology-weight").value = style.style.weight;

            document.getElementById("symbology-fillOpacity-Output").value = style.style.fillOpacity;
            document.getElementById("symbology-opacity-Output").value = style.style.opacity;

            break;
          case "choropleth":
            document.getElementById("symbology-type").value = style.type;

            document.getElementById("choroplethDetail").style.display = "block";
            document.getElementById("singleSymbolDetail").style.display = "none";
            break;
        }
      });
    }

  });

}


function addLayerToMap(map, name, feature_type, json, layer_style, count) {

  let layerProperties = [];
  if (count != 0) {
    for (let property of Object.keys(json.features[0].properties)) {
      layerProperties.push(property);
    }
  }

  let layer;

  if (feature_type == "Point") {
    layer = L.geoJson(json, {
      pointToLayer: function (feature, latlng) {
          return L.circleMarker(latlng, layer_style);
      },
      onEachFeature: function (feature, layer) {
        //feature.mojeID = "1",
        layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
      },
      style: style(feature_type, layer_style)
    }).addTo(map);
  } else {
    layer = L.geoJSON(json,
      {
        onEachFeature: function (feature, layer) {
          layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
        },

        style: style(feature_type, layer_style)
      }
    ).addTo(map);
  }


  if (layer_style.type == "choropleth") {
    layer.setStyle({"weight": 0, "fillOpacity": 0})


    let layer_choropleth = L.choropleth(json, {
    	valueProperty: layer_style.style.valueProperty,
    	scale: [layer_style.style.scale1, layer_style.style.scale2],
    	steps: layer_style.style.steps,
    	mode: layer_style.style.valueProperty.mode,
    	style: {
    		color: layer_style.style.color,
    		weight: layer_style.style.weight,
    		fillOpacity: layer_style.style.fillOpacity
    	},
    	onEachFeature: function(feature, layer) {
        layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
    	}
    }).addTo(map);
  }


  switch (feature_type) {
    case "Point":
      map.addControl(new L.Control.Draw({
        edit: {
          featureGroup: layer
        },
        draw: {
          polygon : false,
          polyline : false,
          rectangle : false,
          circle : false,
          circlemarker : false
        }
      }));
      break;
    case "PolyLine":
      map.addControl(new L.Control.Draw({
        edit: {
          featureGroup: layer
        },
        draw: {
          polygon : false,
          marker : false,
          rectangle : false,
          circle : false,
          circlemarker : false
        }
      }));
      break;
    case "Polygon":
      map.addControl(new L.Control.Draw({
        edit: {
          featureGroup: layer
        },
        draw: {
          marker : false,
          polyline : false,
          rectangle : false,
          circle : false,
          circlemarker : false
        }
      }));
      break;
    default:
      map.addControl(new L.Control.Draw({
        edit: {
          featureGroup: layer
        }
      }));
      break;
  }


  map.on(L.Draw.Event.CREATED, function (e) {
    console.log("CREATE");

    newlayer = e.layer;

    console.log(newlayer);

    let layerMax = layer.toGeoJSON();
    let msFidArr = [];
    for (let i = 0; i < layerMax.features.length; i++) {
      msFidArr.push(layerMax.features[i].properties.ms_fid);
    }
    let maxMsFid = Math.max.apply(Math, msFidArr);


    feature = newlayer.feature = newlayer.feature || {}; // Initialize feature

    feature.type = feature.type || "Feature"; // Initialize feature.type
    let props = feature.properties = feature.properties || {}; // Initialize feature.properties

    layerProperties.forEach(function (property, index) {
      if (property == "ms_fid") {
        feature.properties[property] = maxMsFid + 1;
      } else {
        feature.properties[property] = "";
      }
    });

    layer.addLayer(newlayer);

    newLayerGeometry = newlayer.toGeoJSON().geometry;
    newLayerProperties = newlayer.toGeoJSON().properties;

    console.log(newLayerGeometry);

    let layer_id = getUrlParam('id');

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerGeomAdd", layer_id: getUrlParam('id'), geometry: newLayerGeometry, properties: newLayerProperties},
      success: function(data){
        console.log(data);
        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {

          showNotif(data_parsed.status, data_parsed.message);
          location.href = "layerView?id=" + layer_id;
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



  map.on(L.Draw.Event.EDITED, function (e) {
    console.log("EDIT");

    let editedLayers = e.layers;

    editedLayers.eachLayer(function (editedLayer) {

      let layer_id = getUrlParam('id');
      let geometry = editedLayer.toGeoJSON().geometry;
      let ms_fid = editedLayer.toGeoJSON().properties.ms_fid;

      $.ajax({
        method:"POST",
        url: 'server/router.php',
        data: {act: "layerGeomUpdate", layer_id: getUrlParam('id'), ms_fid: ms_fid, geometry: geometry},
        success: function(data){

          let data_parsed = jQuery.parseJSON (data);
          if (data_parsed.status == "OK") {

            showNotif(data_parsed.status, data_parsed.message);

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


  map.on(L.Draw.Event.DELETED, function (e) {
    console.log("DELETE");

    let deletedLayers = e.layers;


    deletedLayers.eachLayer(function (deletedLayer) {

      let layer_id = getUrlParam('id');
      let geometry = deletedLayer.toGeoJSON().geometry;
      let ms_fid = deletedLayer.toGeoJSON().properties.ms_fid;

      $.ajax({
        method:"POST",
        url: 'server/router.php',
        data: {act: "layerGeomDelete", layer_id: getUrlParam('id'), ms_fid: ms_fid, geometry: geometry},
        success: function(data){

          let data_parsed = jQuery.parseJSON (data);
          if (data_parsed.status == "OK") {

            showNotif(data_parsed.status, data_parsed.message);

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


  if (count != 0) {
    map.fitBounds(layer.getBounds());
  }

  hideLoading();
}


function style(feature_type, layer_style) {
  //console.log(JSON.stringify(layer_style.style));
  switch(feature_type) {
    case "Point":

      if (layer_style.type == "singleSymbol") {
        return {
          radius: layer_style.style.radius,
          fillColor: layer_style.style.fillColor,
          weight: layer_style.style.weight,
          opacity: layer_style.style.opacity,
          color: layer_style.style.color,
          fillOpacity: layer_style.style.fillOpacity
        };
      }

      break;
    case "PolyLine":

      if (layer_style.type == "singleSymbol") {
        return {
            color: layer_style.style.color,
            weight: layer_style.style.weight,
            opacity: layer_style.style.opacity,
        };
      }

      break;
    case "Polygon":

      if (layer_style.type == "singleSymbol") {
        return {
            fillColor: layer_style.style.fillColor,
            weight: layer_style.style.weight,
            opacity: layer_style.style.opacity,
            color: layer_style.style.color,
            fillOpacity: layer_style.style.fillOpacity
        };
      }

      break;
  }
}
