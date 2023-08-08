jQuery(window).on("load", function(){

  // $('#kraje_cr').val(this.checked);
  //
  // $('#kraje_cr').change(function() {
  //   if(this.checked) {
  //     layer_choropleth.addTo(map);
  //   } else {
  //     layer_choropleth.removeFrom(map);
  //   }
  // });

  if ($(window).width() < 600) {
     document.getElementById("sidebar").classList.add('sidebar_mobile');
     document.getElementById("navBtn").classList.add('navBtn_mobile');
     document.getElementById("navBtn").classList.remove('navBtn');

     invalidate();
  }
  else {
  }
});

$(window).on('resize', function(){
  if ($(window).width() > 600) {
    if (sidebar.offsetWidth > 0) {
      document.getElementById("sidebar").classList.remove('sidebar_mobile');
      document.getElementById("navBtn").classList.remove('navBtn_mobile');
      document.getElementById("navBtn").classList.add('navBtn');
      document.getElementById("sidebar").classList.add('sidebar');

    } else {
      document.getElementById("sidebar").classList.remove('sidebar');
      document.getElementById("navBtn").classList.remove('navBtn_mobile');
      document.getElementById("navBtn").classList.remove('navBtn_mobile_small');
      document.getElementById("navBtn").classList.add('navBtn_small');
      document.getElementById("navBtn").classList.add('navBtn');
      document.getElementById("sidebar").classList.add('sidebar');
      document.getElementById("sidebar").classList.add('sidebar_small');

    }

     invalidate();
  }
  else {
    if (sidebar.offsetWidth > 0) {
      document.getElementById("sidebar").classList.add('sidebar_mobile');
      document.getElementById("sidebar").classList.remove('sidebar');
      document.getElementById("navBtn").classList.remove('navBtn');
      document.getElementById("navBtn").classList.add('navBtn_mobile');
    } else {
      document.getElementById("sidebar").classList.add('sidebar');
      document.getElementById("sidebar").classList.remove('sidebar_mobile');
      document.getElementById("navBtn").classList.remove('navBtn_small');
      document.getElementById("navBtn").classList.remove('navBtn');
      document.getElementById("navBtn").classList.add('navBtn_mobile');
      document.getElementById("navBtn").classList.add('navBtn_mobile_small');
    }

  }
});

$(document).ready(function() {
  // $('#kraje_cr').val(this.checked);
  // $('#bus_Lines').val(this.checked);
  // $('#tram_Stops').val(this.checked);
  // $('#bus_Stops').val(this.checked);
  // $('#zona_5');
  // $('#zona_10');
  // $('#zona_15');
  // $('#zona_30');
  // $('#tram_tracks');
  //
  // $('#kraje_cr').change(function() {
  //   if(this.checked) {
  //     layer_choropleth.addTo(map);
  //   } else {
  //     layer_choropleth.removeFrom(map);
  //   }
  // });
  //
  // $('#bus_Lines').change(function() {
  //   if(this.checked) {
  //     bus_Lines.addTo(map);
  //   } else {
  //     bus_Lines.removeFrom(map);
  //   }
  // });
  //
  // $('#tram_Stops').change(function() {
  //   if(this.checked) {
  //     tram_Stops_Cluster.addTo(map);
  //   } else {
  //     tram_Stops_Cluster.removeFrom(map);
  //   }
  // });
  //
  // $('#bus_Stops').change(function() {
  //   if(this.checked) {
  //     bus_Stops_Cluster.addTo(map);
  //   } else {
  //     bus_Stops_Cluster.removeFrom(map);
  //   }
  // });
  //
  // $('#zona_10').change(function() {
  //   if(this.checked) {
  //     zona10.addTo(map);
  //   } else {
  //     zona10.removeFrom(map);
  //   }
  // });
  //
  // $('#zona_15').change(function() {
  //   if(this.checked) {
  //     zona15.addTo(map);
  //   } else {
  //     zona15.removeFrom(map);
  //   }
  // });
  //
  // $('#zona_30').change(function() {
  //   if(this.checked) {
  //     zona30.addTo(map);
  //   } else {
  //     zona30.removeFrom(map);
  //   }
  // });
  //
  // $('#zona_5').change(function() {
  //   if(this.checked) {
  //     zona5.addTo(map);
  //   } else {
  //     zona5.removeFrom(map);
  //   }
  // });
  //
  // $('#tram_tracks').change(function() {
  //   if(this.checked) {
  //     tram_tracks.addTo(map);
  //   } else {
  //     tram_tracks.removeFrom(map);
  //   }
  // });
});


const sidebar = document.querySelector('.sidebar');
const map_container = document.querySelector('.map');
const navBtn = document.querySelector('.navBtn');


function toggleNav() {

  if (sidebar.offsetWidth > 0) {
    document.getElementById("arrow").innerText = ">";
  } else {
    document.getElementById("arrow").innerText = "<";
  }

  if ($(window).width() < 600) {
    sidebar.classList.toggle('sidebar_small');
    map_container.classList.toggle('map_large');
    navBtn.classList.toggle('navBtn_mobile_small');
  } else {
    sidebar.classList.toggle('sidebar_small');
    map_container.classList.toggle('map_large');
    navBtn.classList.toggle('navBtn_small');
  }

  invalidate();

}

function invalidate() {
  setInterval(function () {
    map.invalidateSize({animate: false});
  }, 0);
}

function showLayers() {

  if(!map.hasLayer(layer_choropleth)) {
    layer_choropleth.addTo(map);
    $("#kraje_cr").prop("checked", true);
  }

}

function hideLayers() {

  if(map.hasLayer(layer_choropleth)) {
    layer_choropleth.removeFrom(map);
    $("#kraje_cr").prop("checked", false);
  }

}
