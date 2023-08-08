$(document).ready(function() {

  getListOfLayers();
  getListOfData();


  $('#layerEmptyForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let name = document.getElementById("name-new").value;
    let description = document.getElementById("description-new").value;
    let type = document.getElementById("type-new").value;
    let layer_type = "new";

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerAdd", layer_type: layer_type, name: name, description: description, type: type},
      success: function(data){
        console.log(data);
        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-layer-empty').modal('hide');
          $("#dataTable").dataTable().fnDestroy();
          getListOfLayers();
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


  $('#layerDataForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let name = document.getElementById("name-data").value;
    let description = document.getElementById("description-data").value;
    let source = document.getElementById("source-data").value;
    let layer_type = "data";

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "layerAdd", layer_type: layer_type, name: name, description: description, source: source},
      success: function(data){
        console.log(data);
        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-layer-data').modal('hide');
          $("#dataTable").dataTable().fnDestroy();
          getListOfLayers();
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


function getListOfLayers() {

  showLoading();

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: 'layers'},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      renderListOfLayers(data_parsed.data);
      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}

function getListOfData() {

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "data"},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      populateDataSelect("source-data", data_parsed.data);
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}


function renderListOfLayers(data) {
  $('#dataTable').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    language: {
        "zeroRecords": "No matching layers found",
    },
    data: data,
    columns: [
      {
        data: "name",
        render: function(data, type, row, full, meta){
                  data = '<a href="layerView?id=' + row.layer_id + '">' + data + '</a>';
                  return data;
                }
      },
      {
        data: "description",
        render: function(data, type, row, full, meta){
                  data = truncateString(data, 100);
                  return data;
                }
      },
      { data: "feature_type" },
      { data: "cdate" }
    ],
  });
}
