@extends('common_template.layout')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .stat-container {
        background-color: #f0f8ff;
        border: 1px solid #b3d7ff;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .stat-header {
        font-weight: bold;
        font-size: 22px;
        margin-bottom: 20px;
        color: #004080;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        flex: 1;
        text-align: center;
    }

    .stat-value {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .chart-bottom-heading .label {
        background-color: #007bff;
        padding: 5px 10px;
        font-size: 13px;
        border-radius: 4px;
    }

    @media (max-width: 600px) {
        .stat-row {
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 0 0 48%;
            margin-bottom: 10px;
        }
    }
</style>
@endsection

@section('content')

<!-- First Box: Account Summary and Filter -->
<div class="stat-container">
    <div class="stat-header">Account Overview</div>

    <!-- Filter Form -->
    <form id="filterForm" class="form-inline mb-4">
        <label for="start_date">From:</label>
        <input type="text" name="start_date" id="start_date" class="input-small" placeholder="yyyy-mm-dd" required>

        <label for="end_date" class="ml-2">To:</label>
        <input type="text" name="end_date" id="end_date" class="input-small" placeholder="yyyy-mm-dd" required>

        <label for="account_id" class="ml-2">Account:</label>
        <select name="account_id" id="account_id" class="input-medium" required>
            <option value="">-- Select Account --</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary ml-2">Filter</button>
    </form>

    <!-- Stat Boxes Row -->
    <div class="stat-row">
        <div class="stat-box">
            <div class="stat-value" id="opening_balance">₹0.00</div>
            <div class="chart-bottom-heading"><span class="label label-info">Opening Balance</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="total_income">₹0.00</div>
            <div class="chart-bottom-heading"><span class="label label-info">Total Income</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="total_expense">₹0.00</div>
            <div class="chart-bottom-heading"><span class="label label-info">Total Expense</span></div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="total_balance">₹0.00</div>
            <div class="chart-bottom-heading"><span class="label label-info">Total Balance</span></div>
        </div>
    </div>
</div>

<!-- Second Box: Chart & Financial Year Filter -->
<div class="stat-container mt-4">
    <div class="stat-header">Income vs Expense (Financial Year)</div>

    <form id="chartForm" class="form-inline mb-3">
        <label for="financial_year" class="mr-2">Financial Year:</label>
        <select id="financial_year" name="financial_year" class="input-medium" required>
            <option value="">-- Select Year --</option>
            @for($year = date('Y'); $year >= 2020; $year--)
                <option value="{{ $year }}">{{ $year }} - {{ $year+1 }}</option>
            @endfor
        </select>
    </form>

    <canvas id="incomeExpenseChart" height="100"></canvas>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<script>

    // ========================
    // Initialize Datepickers
    // ========================
    flatpickr("#start_date", { dateFormat: "Y-m-d" });
    flatpickr("#end_date", { dateFormat: "Y-m-d" });

    // ========================
    // Global Variables
    // ========================
    let chart; // Chart.js instance

    // ========================
    // Submit Handler: Account Stats Box
    // ========================
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const start = $('#start_date').val();
        const end = $('#end_date').val();
        const accountId = $('#account_id').val();

        $.ajax({
            url: "{{ route('dashboard.stats') }}",
            type: "POST",
            data: {
                start_date: start,
                end_date: end,
                account_id: accountId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#opening_balance').text("₹" + response.opening_balance);
                $('#total_income').text("₹" + response.total_income);
                $('#total_expense').text("₹" + response.total_expense);
                $('#total_balance').text("₹" + response.total_balance);
            },
            error: function (err) {
                console.error("Error fetching stats:", err);
            }
        });
    });

    // ========================
    // Render Income vs Expense Chart
    // ========================
    function renderChart(labels, incomeData, expenseData) {
        if (chart) chart.destroy(); // Destroy previous chart instance

        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income',
                        backgroundColor: '#28a745',
                        data: incomeData
                    },
                    {
                        label: 'Expense',
                        backgroundColor: '#dc3545',
                        data: expenseData
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // ========================
    // Render Dummy Chart
    // ========================
    function showDummyChart() {
        const months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
        const dummyIncome = Array(12).fill(0);
        const dummyExpense = Array(12).fill(0);
        renderChart(months, dummyIncome, dummyExpense);
    }

    // ========================
    // Chart Update on Dropdown Change
    // ========================
    $('#financial_year, #account_id').on('change', function () {
        const accountId = $('#account_id').val();
        const year = $('#financial_year').val();

        if (!year || !accountId) {
            showDummyChart();
            return;
        }

        $.ajax({
            url: "{{ route('dashboard.monthlyChart') }}",
            method: "POST",
            data: {
                account_id: accountId,
                financial_year: year,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                let data = response.data || {};

                if (data.months && data.income && data.expense) {
                    renderChart(data.months, data.income, data.expense);
                } else {
                    console.warn('Incomplete data received:', response);
                    showDummyChart();
                }
            },

            error: function (xhr) {
                console.error('Chart loading failed:', xhr);
                showDummyChart();
            }
        });
    });

    // ========================
    // Reset Financial Year Dropdown & Chart
    // ========================
    // $('#account_id').on('change', function () {
    //     $('#financial_year').val('');
    //     showDummyChart();
    // });

    // ========================
    // Initial Dummy Chart on Page Load
    // ========================
    showDummyChart();
</script>

@endsection
