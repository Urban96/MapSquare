$(document).ready(function() {

  getListOfMaps();

  $('#newMapForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();

    let name = document.getElementById("name-new").value;
    let description = document.getElementById("description-new").value;

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: {act: "mapAdd", name: name, description: description},
      success: function(data){

        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-new-map').modal('hide');
          $("#dataTable").dataTable().fnDestroy();
          getListOfMaps();
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


function getListOfMaps() {

  showLoading();

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: "maps"},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      renderListOfMaps(data_parsed.data);
      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}


function renderListOfMaps(data) {
  $('#dataTable').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    language: {
        "zeroRecords": "No matching maps found",
    },
    data: data,
    columns: [
      {
        data: "name",
        render: function(data, type, row, full, meta){
                  data = '<a href="mapView?id=' + row.map_id + '">' + data + '</a>';
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
      { data: "cdate" }
    ],
  });
}
