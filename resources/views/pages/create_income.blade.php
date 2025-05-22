@extends('common_template.layout')

@section('title', isset($income) ? 'Edit Income' : 'Create Income')

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
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">{{ isset($income) ? 'Edit Income' : 'Add Income' }}</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <form class="form-horizontal" id="incomeForm">
                    @csrf
                    @if(isset($income))
                        @method('PUT')
                        <input type="hidden" id="income_id" value="{{ $income->id }}">
                    @endif

                    <fieldset>
                        <legend>{{ isset($income) ? 'Edit Income' : 'Add Income' }}</legend>

                        <!-- Account Selection -->
                        <div class="control-group">
                            <label class="control-label" for="account_id">Account Selection</label>
                            <div class="controls">
                                <select class="input-xlarge" id="account_id" name="account_id">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ isset($income) && $income->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="control-group">
                            <label class="control-label" for="title">Title</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="title" type="text" name="title"
                                    value="{{ $income->title ?? '' }}" placeholder="Enter Title">
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="control-group">
                            <label class="control-label" for="date">Date</label>
                            <div class="controls">
                                <input class="input-xlarge" id="date" type="date" name="date"
                                    value="{{ isset($income) ? $income->date : '' }}">
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="control-group">
                            <label class="control-label" for="amount">Amount</label>
                            <div class="controls">
                                <input class="input-xlarge" id="amount" type="number" name="amount"
                                    value="{{ $income->amount ?? '' }}" placeholder="Enter Amount">
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="control-group">
                            <label class="control-label" for="note">Note</label>
                            <div class="controls">
                                <textarea class="input-xlarge" id="note" name="note" placeholder="Optional note...">{{ $income->note ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">{{ isset($income) ? 'Update' : 'Submit' }}</button>
                            <a href="{{ route('income.view') }}" class="btn">Cancel</a>
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
    $(document).ready(function () {
        $("#incomeForm").validate({
            rules: {
                account_id: "required",
                title: {
                    required: true,
                    maxlength: 255
                },
                date: "required",
                amount: {
                    required: true,
                    number: true,
                    min: 0
                }
            },
            messages: {
                account_id: "Please select an account",
                title: {
                    required: "Please enter a title",
                    maxlength: "Title cannot exceed 255 characters"
                },
                date: "Please select a date",
                amount: {
                    required: "Please enter amount",
                    number: "Enter a valid number",
                    min: "Amount must be non-negative"
                }
            },
            submitHandler: function (form) {
                let isEdit = $("#income_id").length > 0;
                let url = isEdit
                    ? "{{ url('/income') }}/" + $("#income_id").val()
                    : "{{ route('income.store') }}";
                let method = isEdit ? 'POST' : 'POST'; // Method override via @method('PUT') in form

                $.ajax({
                    url: url,
                    type: method,
                    data: $(form).serialize(),
                    success: function (res) {
                        alert(res.message);
                        window.location.href = "{{ route('income.view') }}";
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                alert(value[0]);
                            });
                        } else {
                            alert("Something went wrong.");
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
