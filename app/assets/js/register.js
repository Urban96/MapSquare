$(document).ready(function () {
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let cpassword = document.getElementById("confirm_password").value;

        if (password != cpassword) {
          showNotif("ERROR", "Passwords do not match.");
          throw new Error("Passwords do not match.");
        }

        $.ajax({
          type: "POST",
          url: '../server/router.php',
          data: {act: "register", email: email, password: password},
          success: function(data){
            console.log(data);
            let data_parsed = jQuery.parseJSON (data);
            showNotif(data_parsed.status, data_parsed.message);
          }
        });

    });
});
