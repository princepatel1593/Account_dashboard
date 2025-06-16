<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot-Password</title>

    <!-- Bootstrap & jQuery CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- Validation Styling -->
    <style>
        label.error {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        input.error {
            border-color: red;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="col-md-4 offset-md-4">
        <h4 class="text-center mb-4">Forgot Password</h4>

        <div id="messageArea"></div>

        <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label>Email address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
        </form>

        <p class="mt-3 text-center">
            <a href="{{ route('login') }}">Back to Login</a>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#forgotForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                let $form = $(form);
                let actionUrl = $form.attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        $('.alert').remove();
                        const alertBox = $('<div class="alert alert-success">Reset link sent to your email.</div>');
                        $form.before(alertBox);
                        $form.trigger('reset');

                        setTimeout(() => {
                            alertBox.fadeOut(500, function () {
                                $(this).remove();
                            });
                        }, 5000);
                    },
                    error: function (xhr) {
                        $('.alert').remove();
                        let error = xhr.responseJSON.errors?.email ? xhr.responseJSON.errors.email[0] : 'Something went wrong';
                        const alertBox = $('<div class="alert alert-danger">' + error + '</div>');
                        $form.before(alertBox);

                        setTimeout(() => {
                            alertBox.fadeOut(500, function () {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                });
            }
        });
    });
</script>

</body>
</html>
