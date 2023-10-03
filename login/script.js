$(document).ready(function () {
  $("#status-signup").on("click", "#next-signup", function () {
    var username = $("#username-signup").val();
    var password = $("#password-signup").val();
    $.post(
      "signup.php",
      {
        username: username,
        password: password,
      },
      function (data) {
        $("#status-signup").html(data);
      }
    );
  });  

  // Posting name and email
  $("#status-name").on("click", "#next-signup-name", function () {
    var username = $("#username-signup").val();
    var name = $("#name").val();
    var email = $("#email").val();
    $.post("postName.php", {
        username: username,
        name: name,
        email: email
      }, function (data) {
        $("#status-name").html(data);
      });
  });  

  // Posting for login
  $("#post-login").click(function (event) {
    event.preventDefault();
    var username = $("#username-login").val();
    var password = $("#password-login").val();

    $.ajax({
      type: "POST",
      url: "login.php",
      data: {
        username: username,
        password: password,
      },
      dataType: "text",
      success: function(data) {
        if (data === "success") {
          window.location.href = "../home/home.php";
        } else {
          $("#status-login").html(data);
        }
      },
      error: function(xhr, status, error) {
        console.log("Error:", status, error);
        $("#status-login").html("An error occurred during login. Please try again later.");
      }
    });
  })

  // Password validation
  $("#password-signup").keyup(function () {
    var currentPassword = $("#password-signup").val();
    $.post(
      "validatePassword.php",
      {
        currentPassword: currentPassword,
      },
      function (data) {
        $("#status-signup").html(data);
      }
    );
  });

  // Password validation through username (wierdly necessary)
  $("#username-signup").keyup(function () {
    var currentPassword = $("#password-signup").val();
    $.post(
      "validatePassword.php",
      {
        currentPassword: currentPassword,
      },
      function (data) {
        $("#status-signup").html(data);
      }
    );
  });

  // Email validation
  $("#email").keyup(function () {
    var email = $("#email").val();
    $.post("validateEmail.php", {
        email: email,
    }, function (data) {
      $("#status-name").html(data);
    });
  });
})