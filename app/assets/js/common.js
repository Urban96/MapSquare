$(function(){
  let pathname = window.location.pathname;
  let pathArray = pathname.split("/");
  let url = pathArray[pathArray.length - 1];

  $("#menuDiv").load("assets/partials/menu.html", function() {
    switch (url) {
      case "dashboard":
      case "dashboard.php":
        document.getElementById("menuDashboard").classList.add("active");
        break;
      case "maps":
      case "maps.php":
      case "mapView":
      case "mapView.php":
        document.getElementById("menuMaps").classList.add("active");
        break;
      case "layers":
      case "layers.php":
      case "layerView":
      case "layerView.php":
        document.getElementById("menuLayers").classList.add("active");
        break;
      case "data":
      case "data.php":
      case "dataView":
      case "dataView.php":
        document.getElementById("menuData").classList.add("active");
        break;
      case "analysis":
      case "analysis.php":
        document.getElementById("menuAnalysis").classList.add("active");
        break;
      case "market":
      case "market.php":
        document.getElementById("menuMarket").classList.add("active");
        break;
      case "docs":
      case "docs.php":
        document.getElementById("menuDocs").classList.add("active");
        break;
      case "blog":
      case "blog.php":
        document.getElementById("menuBlog").classList.add("active");
        break;
      case "contact":
      case "contact.php":
        document.getElementById("menuContact").classList.add("active");
        break;
      default:
        break;
    }
  });
});


function showNotif(status, message, x = 'center', y = 'top', duration = 25000) {
  let notyf = new Notyf({duration: duration, position: {
      x: x,
      y: y,
  }});

  if (status == "OK") {
    let notification = notyf.success(message);
  } else {
    let notification = notyf.error(message);
  }
}

function showLoading () {
  document.getElementById("loadingScreen").classList.add("show");
}

function hideLoading () {
  document.getElementById("loadingScreen").classList.remove("show");
}

function getFileExtension(filename) {
  let parts = filename.split('.');
  return parts[parts.length - 1];
}

function bytesToMegaBytes(bytes) {
  return bytes / (1024*1024);
}

function populateDataSelect(id, data) {
  let select = document.getElementById(id);

  for (let key in data) {
    let obj = data[key];

    let el = document.createElement("option");
    el.textContent = obj.name;
    el.value = obj.data_id;
    select.appendChild(el);
  }
}

function populateLayerSelect(id, data, allowed = ["Point", "PolyLine", "Polygon"]) {
  let select = document.getElementById(id);

  for (let key in data) {
    let obj = data[key];
    if (allowed.includes(obj.feature_type)) {
      let el = document.createElement("option");
      el.textContent = obj.name;
      el.value = obj.layer_id;
      select.appendChild(el);
    }
  }
}

function truncateString(string, number) {
   if (string.length > number) {
      return string.substring(0, number) + '...';
   }
   return string;
};

function getUrlParam(param) {
  let queryString = window.location.search;
  let urlParams = new URLSearchParams(queryString);
  return urlParams.get(param);
}
