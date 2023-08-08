$(document).ready(function () {
    $('#lostPasswordForm').on('submit', function(e) {
        e.preventDefault();

        let email = document.getElementById("email").value;

        $.ajax({
          type: "POST",
          url: '../server/router.php',
          data: {act: "lostPassword", email: email},
          success: function(data){
            console.log(data);
            let data_parsed = jQuery.parseJSON (data);
            showNotif(data_parsed.status, data_parsed.message);
          }
        });

    });
});
