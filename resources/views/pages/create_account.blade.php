@extends('common_template.layout')

@section('title', isset($account) ? 'Edit Account' : 'Create Account')

@section('styles')
<style>
    label.error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }
</style>
@endsection 

@section('content')
<div class="row-fluid">
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">{{ isset($account) ? 'Edit' : 'Add' }} Account</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <form id="createAccountForm" class="form-horizontal" method="POST">
                    @csrf
                    @if(isset($account)) 
                        @method('PUT') <!-- Use PUT for update -->
                    @endif
                    <fieldset>
                        <legend>{{ isset($account) ? 'Edit' : 'Add' }} Account</legend>

                        <!-- Account Name Field -->
                        <div class="control-group">
                            <label class="control-label" for="account_name">Account Name</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="account_name" type="text" name="account_name" 
                                       value="{{ isset($account) ? $account->account_name : '' }}" placeholder="Enter Account Name">
                            </div>
                        </div>

                        <!-- Opening Balance Field -->
                        <div class="control-group">
                            <label class="control-label" for="opening_balance">Opening Balance</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="opening_balance" type="text" name="opening_balance" 
                                       value="{{ isset($account) ? $account->opening_balance : '' }}" placeholder="Enter Opening Balance">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">{{ isset($account) ? 'Update' : 'Submit' }}</button>
                            <a href="{{ route('accounts.view') }}" class="btn" id="cancelBtn">Cancel</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // Validate the form using jQuery Validation plugin
            $("#createAccountForm").validate({
                rules: {
                    account_name: {
                        required: true,
                        maxlength: 255
                    },
                    opening_balance: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    account_name: {
                        required: "Please enter an account name",
                        maxlength: "Account name cannot exceed 255 characters"
                    },
                    opening_balance: {
                        required: "Please enter an opening balance",
                        number: "Please enter a valid number"
                    }
                },
                submitHandler: function(form) {
                    // Submit form via AJAX
                    $.ajax({
                        url: '{{ isset($account) ? route("accounts.update", $account->id) : route("accounts.store") }}',
                        type: 'POST',
                        data: $(form).serialize(),  // Serialize form data
                        success: function(response) {
                            // On success, show alert message and redirect
                            alert('{{ isset($account) ? 'Account Details Updated successfully!' : 'Account  Details created successfully!' }}');
                            window.location.href = '{{ route("accounts.view") }}';  // Redirect back to the accounts view page
                        },
                        error: function(xhr, status, error) {
                            // Handle errors here
                            alert("An error occurred: " + error);
                        }
                    });
                }
            });

            // Handle cancel action with AJAX (without page reload)
            $("#cancelBtn").on("click", function(e) {
                e.preventDefault(); // Prevent default behavior of cancel button
                window.location.href = '{{ route("accounts.view") }}';  // Redirect to accounts list page
            });
        });
    </script>
@endsection
