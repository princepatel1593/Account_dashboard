<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & jQuery CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- <script src="{{ asset('assets/js/jquery-1.9.1.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-box {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h3 class="text-center mb-4">LOG IN</h3>
            <div id="loginAlert" class="alert d-none"></div>

            <form id="loginForm">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                    <small class="text-danger error-email"></small>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-danger error-password"></small>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
            <p class="mt-3 text-center">
                <a href="{{route('password.request')}}">Forgot Your Password</a>
            </p>
            <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>
            
        </div>
    </div>
    <script>
        $(document).ready(function () {

            // Set CSRF token for every AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email",
                        email: "Enter a valid email"
                    },
                    password: {
                        required: "Please enter your password"
                    }
                },
                errorPlacement: function (error, element) {
                    element.closest('.mb-3').find('small').text(error.text());
                },
                success: function (label, element) {
                    $(element).closest('.mb-3').find('small').text('');
                },
                submitHandler: function (form) {
                    $('.text-danger').text('');
                    $('#loginAlert').removeClass('alert-danger alert-success').addClass('d-none');
    
                    $.ajax({
                        url: '/login',
                        method: 'POST',
                        data: $(form).serialize(),
                        success: function (response) {
                            $('#loginAlert')
                                .removeClass('d-none alert-danger')
                                .addClass('alert-success')
                                .text(response.success);
    
                            setTimeout(() => {
                                window.location.href = '/dashboard';
                            }, 1500);
                        },
                        error: function (xhr) {
                            if (xhr.status === 401) {
                                $('#loginAlert')
                                    .removeClass('d-none alert-success')
                                    .addClass('alert-danger')
                                    .text(xhr.responseJSON.error);
                            } else if (xhr.status === 422) {
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    $('.error-' + key).text(value[0]);
                                });
                            } else {
                                $('#loginAlert')
                                    .removeClass('d-none alert-success')
                                    .addClass('alert-danger')
                                    .text('Something went wrong.');
                            }
                        }
                    });
    
                    return false; // prevent default form submission
                }
            });
        });
    </script>
    
</body>
</html>
