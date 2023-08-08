$(document).ready(function() {

  getListOfData();

  $('#uploadForm').on('submit', function(e) {
    e.preventDefault();

    showLoading();
    let formData = new FormData();
    formData.append('file',$('#formFile')[0].files[0]);
    formData.append('act', 'dataUpload');

    let fileExtension = getFileExtension($('#formFile')[0].files[0].name);
    if ((fileExtension != "json") && (fileExtension != "geojson") && (fileExtension != "zip")) {
      hideLoading();
      showNotif("ERROR", "Unsupported file format.");
      throw new Error("Unsupported file format.");
    }

    let fileSize = bytesToMegaBytes($('#formFile')[0].files[0].size);
    if (fileSize > 15) {
      hideLoading();
      showNotif("ERROR", "The file is larger than 15MB");
      throw new Error("The file is larger than 15MB");
    }

    $.ajax({
      method:"POST",
      url: 'server/router.php',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data){
        let data_parsed = jQuery.parseJSON (data);
        if (data_parsed.status == "OK") {
          $('#modal-upload').modal('hide');
          $("#dataTable").dataTable().fnDestroy();
          getListOfData();
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

function getListOfData() {

  $.ajax({
    method:"POST",
    url: 'server/router.php',
    data: {act: 'data'},
    success: function(data){
      let data_parsed = jQuery.parseJSON (data);
      renderListOfData(data_parsed.data);
      hideLoading();
    },
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    }
  });
}

function renderListOfData(data) {
  $('#dataTable').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    language: {
        "zeroRecords": "No matching data found",
    },
    data: data,
    columns: [
      {
        data: "name",
        render: function(data, type, row, full, meta){
                  data = '<a href="dataView?id=' + row.data_id + '">' + data + '</a>';
                  return data;
                }
      },
      { data: "data_type" },
      { data: "feature_type" },
      { data: "cdate" }
    ],
  });
}
