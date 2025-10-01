$(document).ready(function () {
    // Load profile data when page opens
    const username = localStorage.getItem("username");
    if (!username) {
        window.location.href = "login.html"; // redirect if not logged in
    } else {
        $("#username").val(username);

        // Fetch profile from backend
        $.ajax({
            url: "php/profile.php",
            type: "POST",
            data: { action: "get", username: username },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#age").val(response.data.age);
                    $("#dob").val(response.data.dob);
                    $("#contact").val(response.data.contact);
                } else {
                    $("#profileMsg").html(
                        '<div class="alert alert-warning">' + response.message + "</div>"
                    );
                }
            },
            error: function () {
                $("#profileMsg").html(
                    '<div class="alert alert-danger">Error loading profile.</div>'
                );
            },
        });
    }

    // Update profile
    $("#profileForm").submit(function (e) {
        e.preventDefault();
        var age = $("#age").val();
        var dob = $("#dob").val();
        var contact = $("#contact").val();

        $.ajax({
            url: "php/profile.php",
            type: "POST",
            data: {
                action: "update",
                username: username,
                age: age,
                dob: dob,
                contact: contact,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#profileMsg").html(
                        '<div class="alert alert-success">' + response.message + "</div>"
                    );
                } else {
                    $("#profileMsg").html(
                        '<div class="alert alert-danger">' + response.message + "</div>"
                    );
                }
            },
            error: function () {
                $("#profileMsg").html(
                    '<div class="alert alert-danger">Error updating profile.</div>'
                );
            },
        });
    });

    // Logout
    $("#logoutBtn").click(function () {
        localStorage.clear();
        window.location.href = "login.html";
    });
});
