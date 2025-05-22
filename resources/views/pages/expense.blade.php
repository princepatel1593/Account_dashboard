@extends('common_template.layout')

@section('title', 'Expense')

@section('styles')
    <!-- Only loaded on this page -->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/DT_bootstrap.css">
    
@endsection 

@section('content')
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header d-flex justify-content-between align-items-center">
            <div class="muted pull-left"><h5>Expense List</h5></div>
            <a href="{{route('expense.create')}}" class="btn btn-success pull-right active">Add Expense</a>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="expenseTable">
                    <thead>
                        <tr>
                            <th>Account Selection</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->account->account_name ?? 'N/A' }}</td> <!-- Account Name -->
                                <td>{{ $expense->title }}</td> <!-- Expense Title -->
                                <td>{{ $expense->date }}</td> <!-- Expense Date -->
                                <td>{{ $expense->amount }}</td> <!-- Expense Amount -->
                                <td>{{ $expense->note ?? '-' }}</td> <!-- Expense Note -->
                                <td>
                                    <a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-info">Edit</a>
                                    <button class="btn btn-danger delete-expense" data-id="{{ $expense->id }}">Delete</button>
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
    <!-- Only for this page -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/DT_bootstrap.js') }}"></script>

   <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#expenseTable').DataTable();
            // Ensure CSRF token is sent with all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Delete Expense
            $(document).on('click', '.delete-expense', function () {
                if (!confirm('Are you sure you want to delete this expense?')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: "/expense/" + id, // Adjust the URL based on your route
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token
                    },
                    success: function (res) {
                        alert(res.message); // Show success message
                        location.reload(); // Reload the page to reflect changes
                    },
                    error: function () {
                        alert("Failed to delete expense.");
                    }
                });
            });
        });
    </script>

@endsection