$(document).ready(function () {

  let id = getUrlParam('id');
  document.getElementById("pass_string").value = id;

    $('#recoverForm').on('submit', function(e) {
        e.preventDefault();

        let pass_string = document.getElementById("pass_string").value;
        let password = document.getElementById("password").value;
        let cpassword = document.getElementById("confirm_password").value;

        if (password != cpassword) {
          showNotif("ERROR", "Passwords do not match.");
          throw new Error("Passwords do not match.");
        }

        $.ajax({
          type: "POST",
          url: '../server/router.php',
          data: {act: "recover", pass_string: pass_string, password: password, confirm_password: cpassword},
          success: function(data){
            console.log(data);
            let data_parsed = jQuery.parseJSON (data);
            showNotif(data_parsed.status, data_parsed.message);
          }
        });

    });
});
