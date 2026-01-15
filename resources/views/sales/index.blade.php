@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8 p-4 md:p-8">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Sales Transactions</h1>
            <p class="text-slate-500 mt-1">Track and manage all product sales across locations.</p>
        </div>

        @if(auth()->user()->canMakeSales())
            <a href="{{ route('sales.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Record New Sale
            </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <p class="text-sm font-medium text-emerald-700 mb-2">Total Sales Amount</p>
            <h3 class="text-3xl font-bold text-emerald-900">₦{{ number_format($totalSales ?? 0, 2) }}</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
            <p class="text-sm font-medium text-blue-700 mb-2">Total Transactions</p>
            <h3 class="text-3xl font-bold text-blue-900">{{ $sales->total() }}</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm">
            <p class="text-sm font-medium text-purple-700 mb-2">Items Sold</p>
            <h3 class="text-3xl font-bold text-purple-900">{{ number_format($totalQuantity ?? 0) }}</h3>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- Location Filter --}}
            @if(auth()->user()->isOwner())
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                <select name="location_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Product Filter --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Product</label>
                <select name="product_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Receipt Number --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Receipt #</label>
                <input type="text" name="receipt_number" value="{{ request('receipt_number') }}"
                       placeholder="Search receipt..."
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Filter Buttons --}}
            <div class="md:col-span-2 lg:col-span-5 flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition">
                    Apply Filters
                </button>
                <a href="{{ route('sales.index') }}"
                   class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    <th class="px-6 py-4">Receipt #</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Location</th>
                    <th class="px-6 py-4">Quantity</th>
                    <th class="px-6 py-4">Unit Price</th>
                    <th class="px-6 py-4">Total Amount</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Sold By</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $sale)
                <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer clickable-row"
                    data-href="{{ route('sales.show', $sale->id) }}">

                    {{-- Receipt Number --}}
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-bold text-emerald-600">{{ $sale->formatted_receipt_number }}</span>
                    </td>

                    {{-- Product --}}
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $sale->product->name }}</div>
                        <div class="text-xs text-slate-500">{{ $sale->product->id }}</div>
                    </td>

                    {{-- Location --}}
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-700">{{ $sale->location->name }}</span>
                    </td>

                    {{-- Quantity --}}
                    <td class="px-6 py-4">
                        <span class="text-lg font-bold text-slate-900">{{ $sale->quantity }}</span>
                    </td>

                    {{-- Unit Price --}}
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-700">₦{{ number_format($sale->unit_price, 2) }}</span>
                    </td>

                    {{-- Total Amount --}}
                    <td class="px-6 py-4">
                        <span class="text-lg font-bold text-emerald-600">₦{{ number_format($sale->total_amount, 2) }}</span>
                    </td>

                    {{-- Customer --}}
                    <td class="px-6 py-4">
                        @if($sale->customer_name)
                            <div class="font-semibold text-slate-900">{{ $sale->customer_name }}</div>
                            @if($sale->customer_phone)
                                <div class="text-xs text-slate-500">{{ $sale->customer_phone }}</div>
                            @endif
                        @else
                            <span class="text-slate-400 text-sm">Walk-in</span>
                        @endif
                    </td>

                    {{-- Sold By --}}
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $sale->seller->name }}</div>
                        <div class="text-xs text-slate-500">{{ ucwords(str_replace('_', ' ', $sale->seller->role)) }}</div>
                    </td>

                    {{-- Date --}}
                    <td class="px-6 py-4 text-xs text-slate-500">
                        <div class="font-bold text-slate-700">{{ $sale->sold_at->format('M d, Y') }}</div>
                        <div>{{ $sale->sold_at->format('h:i A') }}</div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('sales.show', $sale->id) }}"
                               class="p-2 text-slate-400 hover:text-emerald-600 transition-colors bg-slate-50 rounded-lg"
                               title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('sales.receipt', $sale->id) }}"
                               class="p-2 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 rounded-lg"
                               title="View Receipt"
                               target="_blank">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 text-lg font-semibold mb-2">No sales found</p>
                            @if(auth()->user()->canMakeSales())
                                <a href="{{ route('sales.create') }}" class="text-emerald-600 font-bold hover:underline">
                                    Record your first sale →
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Row Click Handler --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endsection
