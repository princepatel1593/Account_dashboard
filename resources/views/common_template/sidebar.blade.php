<!-- resources/views/common_template/sidebar.blade.php -->
<div class="span3" id="sidebar">
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li class="{{ Route::is('dashboard.view') ? 'active' : '' }}">
            <a href="{{route('dashboard.view')}}"><i class="icon-chevron-right"></i> Dashboard</a>
        </li >
        <li  class="{{ Route::is('accounts.view') ? 'active' : '' }}">
            <a href="{{route('accounts.view')}}"><i class="icon-chevron-right"></i>Accounts</a>
        </li>
        <li class="{{ Route::is('income.view') ? 'active' : '' }}">
            <a href="{{route('income.view')}}"><i class="icon-chevron-right"></i>Income</a>
        </li>
        <li class="{{ Route::is('expense.view') ? 'active' : '' }}">
            <a href="{{route('expense.view')}}"><i class="icon-chevron-right"></i> Expense</a>
        </li>
       
    </ul>
</div>