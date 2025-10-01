$(document).ready(function () {
    $("#loginForm").submit(function (e) {
        e.preventDefault(); // stop normal form submit

        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "php/login.php",
            type: "POST",
            data: { email: email, password: password },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    localStorage.setItem("sessionToken", response.session_token);
                    localStorage.setItem("userEmail", response.email);
                    localStorage.setItem("userName", response.name);

                    window.location.href = "profile.html";
                } else {
                    $("#loginMsg").html(
                        '<div class="alert alert-danger">' + response.message + "</div>"
                    );
                }
            }, // ✅ added missing comma here
            error: function () {
                $("#loginMsg").html(
                    '<div class="alert alert-danger">Server error. Please try again.</div>'
                );
            },
        });
    });
});
