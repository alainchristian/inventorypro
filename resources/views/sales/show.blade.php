@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 p-4 md:p-8">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('sales.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Sale Details</h1>
                <p class="text-slate-500 mt-1">Receipt #{{ $sale->formatted_receipt_number }}</p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('sales.receipt', $sale->id) }}"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Receipt
            </a>
        </div>
    </div>

    {{-- Sale Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <p class="text-sm font-medium text-emerald-700 mb-2">Total Amount</p>
            <h3 class="text-3xl font-bold text-emerald-900">₦{{ number_format($sale->total_amount, 2) }}</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
            <p class="text-sm font-medium text-blue-700 mb-2">Quantity Sold</p>
            <h3 class="text-3xl font-bold text-blue-900">{{ $sale->quantity }}</h3>
        </div>
        @if(auth()->user()->isOwner())
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm">
            <p class="text-sm font-medium text-purple-700 mb-2">Profit</p>
            <h3 class="text-3xl font-bold {{ $profit >= 0 ? 'text-purple-900' : 'text-red-900' }}">
                ₦{{ number_format($profit, 2) }}
            </h3>
        </div>
        @else
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm font-medium text-slate-700 mb-2">Unit Price</p>
            <h3 class="text-3xl font-bold text-slate-900">₦{{ number_format($sale->unit_price, 2) }}</h3>
        </div>
        @endif
    </div>

    {{-- Sale Information --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-4">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                <div class="w-1.5 h-4 bg-emerald-600 rounded-full"></div>
                Transaction Information
            </h2>
        </div>

        <div class="p-6 space-y-6">
            {{-- Product & Location --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Product</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                        <p class="text-lg font-bold text-slate-900">{{ $sale->product->name }}</p>
                        <p class="text-sm font-mono text-slate-500">SKU: {{ $sale->product->id }}</p>
                        @if($sale->product->description)
                            <p class="text-sm text-slate-600 mt-2">{{ $sale->product->description }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Location</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                        <p class="text-lg font-bold text-slate-900">{{ $sale->location->name }}</p>
                        <p class="text-sm text-slate-500">{{ ucwords($sale->location->type) }}</p>
                        @if($sale->location->address)
                            <p class="text-sm text-slate-600 mt-2">{{ $sale->location->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pricing Details --}}
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Pricing Breakdown</label>
                <div class="bg-slate-50 border border-slate-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-600">Quantity</td>
                                <td class="px-4 py-3 text-sm font-bold text-slate-900 text-right">{{ $sale->quantity }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-600">Unit Price</td>
                                <td class="px-4 py-3 text-sm font-bold text-slate-900 text-right">₦{{ number_format($sale->unit_price, 2) }}</td>
                            </tr>
                            <tr class="bg-emerald-50">
                                <td class="px-4 py-4 text-base font-bold text-emerald-900">Total Amount</td>
                                <td class="px-4 py-4 text-xl font-black text-emerald-900 text-right">₦{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Customer Information --}}
            @if($sale->customer_name || $sale->customer_phone)
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Customer Information</label>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($sale->customer_name)
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Name</p>
                            <p class="text-base font-bold text-blue-900">{{ $sale->customer_name }}</p>
                        </div>
                        @endif

                        @if($sale->customer_phone)
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Phone</p>
                            <p class="text-base font-bold text-blue-900">{{ $sale->customer_phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Sale Agent --}}
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Sale Information</label>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sold By</p>
                            <p class="text-base font-bold text-slate-900">{{ $sale->seller->name }}</p>
                            <p class="text-sm text-slate-600">{{ ucwords(str_replace('_', ' ', $sale->seller->role)) }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sale Date & Time</p>
                            <p class="text-base font-bold text-slate-900">{{ $sale->sold_at->format('F d, Y') }}</p>
                            <p class="text-sm text-slate-600">{{ $sale->sold_at->format('h:i:s A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($sale->notes)
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Notes</label>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-slate-700">{{ $sale->notes }}</p>
                </div>
            </div>
            @endif

            {{-- Receipt Number --}}
            <div class="border-t border-slate-200 pt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Receipt Number</p>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ $sale->formatted_receipt_number }}</p>
                    </div>
                    <div class="bg-emerald-100 px-4 py-2 rounded-lg">
                        <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-4">
        <a href="{{ route('sales.index') }}"
           class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Sales
        </a>

        <a href="{{ route('sales.receipt', $sale->id) }}"
           target="_blank"
           class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            View & Print Receipt
        </a>
    </div>
</div>
@endsection
