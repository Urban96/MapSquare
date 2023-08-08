$(document).ready(function () {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;


        $.ajax({
          type: "POST",
          url: '../server/router.php',
          data: {act: "login", email: email, password: password},
          success: function(data){
            console.log(data);
            let data_parsed = jQuery.parseJSON (data);
            if (data_parsed.status == "OK") {
             window.location.replace("../dashboard");
            } else {
             showNotif(data_parsed.status, data_parsed.message);
            }
          }
        });

    });
});
