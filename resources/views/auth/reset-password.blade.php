<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

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
        <h4 class="text-center mb-4">Reset Password</h4>

        <div id="messageArea"></div>

        <form id="resetForm" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            <input type="hidden" name="email" value="{{ request()->get('email') }}">

            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#resetForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "[name='password']"
                }
            },
            messages: {
                password: {
                    required: "Please enter a new password",
                    minlength: "Password must be at least 6 characters"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                let actionUrl = $(form).attr('action');
                let formData = $(form).serialize();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#messageArea').html('<div class="alert alert-success">' + response.message + '</div>');
                        $(form).trigger('reset');

                        // Redirect to login after 2 seconds
                        setTimeout(function () {
                            window.location.href = "{{ route('login') }}";
                        }, 2000);
                    },
                    error: function (xhr) {
                        let error = xhr.responseJSON?.errors?.email || ["Something went wrong"];
                        $('#messageArea').html('<div class="alert alert-danger">' + error[0] + '</div>');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
