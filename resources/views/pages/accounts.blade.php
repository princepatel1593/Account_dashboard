@extends('common_template.layout')

@section('title', 'Accounts')

@section('styles')
    <link rel="stylesheet" type="text/css" href="../../../assets/css/DT_bootstrap.css">
@endsection 

@section('content')
<div class="row-fluid">
    <div class="block">
        <div class="navbar navbar-inner block-header d-flex justify-content-between align-items-center">
            <div class="muted pull-left"><h5>Accounts List</h5></div>
            <a href="{{ route('accounts.create') }}" class="btn btn-success pull-right">Add Account</a>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="accountsTable">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Opening Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->account_name }}</td> <!-- Account Name -->
                                <td>{{ $account->opening_balance }}</td> <!-- Opening Balance -->
                                <td>
                                    <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-info">Edit</a>
                                    <button class="btn btn-danger delete-account" data-id="{{ $account->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/DT_bootstrap.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#accountsTable').DataTable();
            // Set CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle delete account button click
            $(document).on('click', '.delete-account', function(e) {
                e.preventDefault();
                var accountId = $(this).data('id');

                if (confirm('Are you sure you want to delete this account?')) {
                    $.ajax({
                        url: '/accounts/' + accountId,
                        type: 'DELETE',
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload(null, false); // Reload without resetting pagination
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred while deleting the account: " + error);
                        }
                    });
                }
            });
        });

    </script>

@endsection
