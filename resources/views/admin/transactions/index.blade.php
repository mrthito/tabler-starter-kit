<x-app-layout :page="__('Transactions')" layout="admin">

    <x-slot name="pretitle">{{ __('Transactions') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage All Financial Transactions') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">{{ __('Create Transaction') }}</a>
    </x-slot>

    <!-- Summary Cards -->
    <div class="row row-cards mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">{{ __('Total Income') }}</div>
                    </div>
                    <div class="h1 mb-3">{{ number_format($summary['total_income'], 2) }} <small
                            class="text-muted">USD</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">{{ __('Total Expense') }}</div>
                    </div>
                    <div class="h1 mb-3">{{ number_format($summary['total_expense'], 2) }} <small
                            class="text-muted">USD</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">{{ __('Net Amount') }}</div>
                    </div>
                    <div class="h1 mb-3">
                        <span class="{{ $summary['net_amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($summary['net_amount'], 2) }}
                        </span>
                        <small class="text-muted">USD</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row row-cards mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Type') }}</label>
                            <select class="form-select" name="type">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>
                                    {{ __('Income') }}</option>
                                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>
                                    {{ __('Expense') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" name="status">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    {{ __('Completed') }}</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    {{ __('Cancelled') }}</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                    {{ __('Failed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Category') }}</label>
                            <select class="form-select" name="category_id">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Date From') }}</label>
                            <input type="date" class="form-control" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Date To') }}</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="btn btn-secondary">{{ __('Reset') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Transactions')" :description="__('All financial transactions')" :action="route('admin.transactions.destroy', 'bulk')">
                <x-table.table>
                    <x-slot name="thead">
                        <tr>
                            <th class="w-1"></th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-date">{{ __('Date') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-ref">{{ __('Reference') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-title">{{ __('Title') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-category">{{ __('Category') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-amount">{{ __('Amount') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Status') }}</button>
                            </th>
                            <th width="10px"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $transaction->id }}">
                                </td>
                                <td class="sort-date">
                                    <div>{{ $transaction->transaction_date->format('M d, Y') }}</div>
                                    <small
                                        class="text-muted">{{ $transaction->transaction_date->format('h:i A') }}</small>
                                </td>
                                <td class="sort-ref">
                                    <code>{{ $transaction->reference_number }}</code>
                                </td>
                                <td class="sort-title">
                                    <div class="fw-bold">{{ $transaction->title }}</div>
                                    @if ($transaction->description)
                                        <small
                                            class="text-muted">{{ \Illuminate\Support\Str::limit($transaction->description, 50) }}</small>
                                    @endif
                                </td>
                                <td class="sort-category">
                                    @if ($transaction->category)
                                        <span class="badge"
                                            style="background-color: {{ $transaction->category->color ?? '#066fd1' }};">
                                            {{ $transaction->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="sort-amount">
                                    <span
                                        class="fw-bold {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->currency }}
                                        {{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td class="sort-status">
                                    @if ($transaction->status === 'completed')
                                        <span class="badge bg-success-lt">{{ __('Completed') }}</span>
                                    @elseif ($transaction->status === 'pending')
                                        <span class="badge bg-warning-lt">{{ __('Pending') }}</span>
                                    @elseif ($transaction->status === 'cancelled')
                                        <span class="badge bg-secondary-lt">{{ __('Cancelled') }}</span>
                                    @else
                                        <span class="badge bg-danger-lt">{{ __('Failed') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $transactions->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $transactions->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
