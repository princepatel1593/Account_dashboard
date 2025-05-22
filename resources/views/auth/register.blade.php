<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & jQuery CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('assets/js/jquery-1.9.1.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-box {
            max-width: 450px;
            margin: 70px auto;
            padding: 30px;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-box">
            <h3 class="text-center mb-4">REGISTER</h3>
            <div id="regAlert" class="alert d-none"></div>

            <form id="registerForm">
                @csrf
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control">
                    <small class="text-danger error-name"></small>
                </div>
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
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                    <small class="text-danger error-password_confirmation"></small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="text-center mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#registerForm').validate({
                rules: {
                    name: { required: true, minlength: 3 },
                    email: { required: true, email: true },
                    password: { required: true, minlength: 6 },
                    password_confirmation: {
                        required: true,
                        equalTo: '[name="password"]'
                    }
                },
                messages: {
                    name: { required: "Please enter your name" },
                    email: { required: "Please enter your email", email: "Enter a valid email" },
                    password: { required: "Please enter a password", minlength: "Password must be at least 6 characters" },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    }
                },
                errorPlacement: function (error, element) {
                    element.closest('.mb-3').find('small').text(error.text());
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    $(element).closest('.mb-3').find('small').text('');
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "{{ route('store.registers') }}",
                        method: "POST",
                        data: $(form).serialize(),
                        success: function (response) {
                            $('#regAlert').removeClass('d-none alert-danger')
                                          .addClass('alert-success')
                                          .text(response.success);

                            $('#registerForm')[0].reset();
                            $('.form-control').removeClass('is-invalid');

                            // Redirect after 5 seconds
                            setTimeout(function () {
                                window.location.href = "{{ route('login') }}";
                            }, 5000);
                        },
                        error: function (xhr) {
                            $('#regAlert').removeClass('d-none alert-success')
                                          .addClass('alert-danger');

                            if (xhr.status === 422) {
                                $('#regAlert').text("Please fix the errors below.");
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    $('.error-' + key).text(value[0]);
                                    $('[name="' + key + '"]').addClass('is-invalid');
                                });
                            } else {
                                $('#regAlert').text('Something went wrong.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
