// $(document).ready(function() {
// 	const queryString = window.location.search;
//   const urlParams = new URLSearchParams(queryString);
//   const map_id = urlParams.get("id");
// });



const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const map_id = urlParams.get("id");

$.ajax({
	method:"POST",
	url: '../app/server/router.php',
	data: {act: "mapGet", id: map_id},
	success: function(data){
		const data_parsed = jQuery.parseJSON (data);
    console.log(data_parsed);
		if(data_parsed.status == "ERROR") {
			location.href = "dashboard";
		}

		document.title = data_parsed.name;

		initMap(data_parsed);

	},
	error: function(xhr, status, error) {
		alert(xhr.responseText);
		hideLoading();
	}
});


function initMap(data) {
	const center = [20, -20];
	const zoom = 3;

	const map = L.map('map', {
		center: center,
		zoom: zoom,
		minZoom: data.options.minZoom,
		maxZoom: data.options.maxZoom,
		zoomControl: false
	});

	map.attributionControl.addAttribution('Built with MapSquare.io');

	L.control.zoom({
		position: 'topright',
		zoomInTitle: 'Zoom in',
		zoomOutTitle: 'Zoom out'
	}).addTo(map);

	if (data.options.plugins.geolocate == 1) {
		L.geolet({
			position: 'topright',
			title: 'Geolocation',
			popup: false
		}).addTo(map);
	}

	if (data.options.plugins.fullscreen == 1) {
		L.control.fullscreen({
		  position: 'topright',
		  title: 'Show me the fullscreen !',
		  titleCancel: 'Exit fullscreen mode'
			}).addTo(map);
	}

	L.control.scale({
		position: 'bottomright',
		imperial: false
	}).addTo(map);


  const baseMaps = {};
  const overlayMaps = {};

  var layerControl = L.control.layers(baseMaps, overlayMaps, {position: 'topleft'}).addTo(map);

  const baseLayers = data.options.basemaps;
  var baseLayersCount = 1;

  for (var key of Object.keys(baseLayers)) {
    if (baseLayers[key] == 1) {
      switch (key) {
        case "carto":
          const carto = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
          });

          if (baseLayersCount == 1) {
            carto.addTo(map);
            baseLayersCount = ++baseLayersCount;
          }
          layerControl.addBaseLayer(carto, "Carto");
          break;

        case "osm_basic":
          const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
          });

          if (baseLayersCount == 1) {
            osm.addTo(map);
            baseLayersCount = ++baseLayersCount;
          }
          layerControl.addBaseLayer(osm, "OpenStreetMap");
          break;

        case "esri_ortophoto":
          const esri_ortophoto = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
          attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
          });

          if (baseLayersCount == 1) {
            esri_ortophoto.addTo(map);
            baseLayersCount = ++baseLayersCount;
          }
          layerControl.addBaseLayer(esri_ortophoto, "Ortophoto");
          break;

        default:

      }

    }
  }


	var legend = L.control.Legend({
  	position: "bottomleft",
    collapsed: false,
    symbolWidth: 24,
    opacity: 1,
    column: 1,
    legends: []
  });


  const layers = data.layers;
  var layersLength = layers.length;

  var layerGroup = new L.featureGroup([]);

  for (var i = 0; i < layersLength; i++) {

      let layer;

			addToLegend(legend, layers[i].name, layers[i].feature_type, layers[i].style);

      if (layers[i].feature_type == "Point") {
        layer = L.geoJson(layers[i].data, {
          pointToLayer: function (feature, latlng) {
              return L.circleMarker(latlng, layers[i].style);
          },
          onEachFeature: function (feature, layer) {
            layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
          },
          style: style(layers[i].feature_type, layers[i].style)
        }).addTo(map);
      } else {
        layer = L.geoJSON(layers[i].data,
          {
            onEachFeature: function (feature, layer) {
              layer.bindPopup('<pre>'+JSON.stringify(feature.properties,null,' ').replace(/[\{\}"]/g,'')+'</pre>');
            },
            style: style(layers[i].feature_type, layers[i].style)
          }
        ).addTo(map);
      }
      layerControl.addOverlay(layer, layers[i].name);
      layer.addTo(layerGroup);
  }

	legend.initialize();
	legend.addTo(map);


  if (data.options.fitBounds == 1) {
    map.fitBounds(layerGroup.getBounds());
	}


}


function addToLegend(legend, name, feature_type, layer_style) {

	switch (feature_type) {
		case "Point":
			legend.options.legends.push( {
				label: name,
				type: "circle",
				radius: layer_style.style.radius,
				color: layer_style.style.color,
				fillColor: layer_style.style.fillColor,
				fillOpacity: layer_style.style.opacity,
				weight: layer_style.style.weight
			})
			break;
		case "PolyLine":
			legend.options.legends.push( {
				label: name,
				type: "polyline",
				color: layer_style.style.color,
				fillColor: layer_style.style.fillColor,
				weight: layer_style.style.weight
				})
			break;
		case "Polygon":
			legend.options.legends.push( {
				label: name,
				type: "rectangle",
				color: layer_style.style.color,
				fillColor: layer_style.style.fillColor,
				weight: layer_style.style.weight
				})
			break;
		default:

	}

}


function style(feature_type, layer_style) {
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
