var pass = document.getElementById("pass");
var flag = 1;
function check(elem) {
  if (elem.value.length > 0) {
    if (elem.value != pass.value) {
      document.getElementById("output").innerText =
        "mots de passe ne correspondent pas.";
      flag = 0;
    } else {
      document.getElementById("output").innerText = " ";
      flag = 1;
    }
  } else {
    document.getElementById("output").innerText =
      "Confirmez Votre Mot de Passe";
    flag = 0;
  }
}
function validate() {
  if (flag == 1) {
    return true;
  } else {
    return false;
  }
}
function showpass() {
  var x = document.getElementById("pass");
  var y = document.getElementById("pass2");
  if (x.type === "password" && y.type === "password") {
    x.type = "text";
    y.type = "text";
    document.getElementById("labelshowpass").style.color = "#3498db";
    document.getElementById("labelshowpass2").style.color = "#3498db";
  } else {
    x.type = "password";
    y.type = "password";
    document.getElementById("labelshowpass").style.color = "black";
    document.getElementById("labelshowpass2").style.color = "black";
  }
}
function triggerClick(e) {
  document.querySelector("#profileImage").click();
}
function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document
        .querySelector("#profileDisplay")
        .setAttribute("src", e.target.result);
      document.querySelector("#profileDisplay").className = "pfps";
    };
    reader.readAsDataURL(e.files[0]);
  }
}
$(document).ready(function () {
  $("#username").blur(function () {
    var username = $(this).val();

    $.ajax({
      url: "check.php",
      method: "POST",
      data: { username: username },
      success: function (data) {
        if (data != "0") {
          $("#availability").html(
            '<span class="text-danger">Username not available</span>'
          );
          $("#register").attr("disabled", true);
        } else {
          $("#availability").html(
            '<span class="text-success">Username Available</span>'
          );
          $("#register").attr("disabled", false);
        }
      },
    });
  });
});
$(function () {
  $("#identifiant").on("keypress", function (e) {
    if (e.which == 32) {
      console.log("Space Detected");
      return false;
    }
  });
});


