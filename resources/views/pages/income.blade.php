@extends('common_template.layout')

@section('title', 'Income List')

@section('styles')
    <link rel="stylesheet" type="text/css" href="../../../assets/css/DT_bootstrap.css">
@endsection 

@section('content')
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header d-flex justify-content-between align-items-center">
            <div class="muted pull-left"><h5>Income List</h5></div>
            <a href="{{ route('income.create') }}" class="btn btn-success pull-right">Add Income</a>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="incomeTable">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $income)
                            <tr>
                                <td>{{ $income->account->account_name ?? 'N/A' }}</td> <!-- Account Name -->
                                <td>{{ $income->title }}</td> <!-- Income Title -->
                                <td>{{ $income->date }}</td> <!-- Income Date -->
                                <td>{{ $income->amount }}</td> <!-- Income Amount -->
                                <td>{{ $income->note ?? '-' }}</td> <!-- Income Note -->
                                <td>
                                    <a href="{{ route('income.edit', $income->id) }}" class="btn btn-info">Edit</a>
                                    <button class="btn btn-danger delete-income" data-id="{{ $income->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/DT_bootstrap.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#incomeTable').DataTable();

            // Ensure CSRF token is sent with all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Delete income
            $(document).on('click', '.delete-income', function () {
                if (!confirm('Are you sure you want to delete this income?')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: "/income/" + id,
                    type: "DELETE",
                    success: function (res) {
                        alert(res.message);
                        location.reload(); // Refresh the table
                    },
                    error: function () {
                        alert("Failed to delete income.");
                    }
                });
            });
        });
    </script>
@endsection
