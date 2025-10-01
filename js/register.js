$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();

        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var age = $('#age').val();
        var dob = $('#dob').val();
        var contact = $('#contact').val();

        console.log({ name, email, password, age, dob, contact }); // debug

        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: { name, email, password, age, dob, contact },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#registerMsg').html('<div class="alert alert-success">' + response.message + '</div>');
                    $('#registerForm')[0].reset();
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 1500);
                } else {
                    $('#registerMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#registerMsg').html('<div class="alert alert-danger">Server error. Please try again.</div>');
            }
        });
    });
});
