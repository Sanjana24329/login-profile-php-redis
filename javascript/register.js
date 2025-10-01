$(document).ready(function() {
    $('#registerForm').submit(function(e){
        e.preventDefault(); // Prevent normal form submission

        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var age = $('#age').val();
        var dob = $('#dob').val();
        var contact = $('#contact').val();

        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: { 
                name: name, 
                email: email,
                password: password, 
                age: age, 
                dob: dob, 
                contact: contact 
            },
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $('#registerMsg').html('<div class="alert alert-success">' + response.message + '</div>');
                    $('#registerForm')[0].reset(); // clear form
                    setTimeout(function(){
                        window.location.href = 'login.html';
                    }, 1500);
                } else {
                    $('#registerMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(){
                $('#registerMsg').html('<div class="alert alert-danger">Server error. Please try again.</div>');
            }
        });
    });
});
