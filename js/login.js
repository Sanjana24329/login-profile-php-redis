$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: { email: email, password: password },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Save session token in cookie for profile.php
                    document.cookie = "session_token=" + response.session_token + "; max-age=3600; path=/";
                    // Store in localStorage for profile.html
                    localStorage.setItem('sessionToken', response.session_token);
                    localStorage.setItem('userEmail', response.email);
                    localStorage.setItem('userName', response.name);
                    window.location.href = 'profile.html';
                } else {
                    $('#loginMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#loginMsg').html('<div class="alert alert-danger">Server error. Please try again.</div>');
            }
        });
    });
});
