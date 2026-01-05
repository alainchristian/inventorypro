@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-8 space-y-6">
    
    {{-- Top Navigation & Utility --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('transfers.index') }}" 
           class="group inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Transfers
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm print:hidden">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Manifest
            </button>
        </div>
    </div>

    {{-- Main Document Card --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden print:shadow-none print:border-slate-300">
        
        {{-- Header Section --}}
        <div class="p-8 border-b border-slate-200 bg-slate-50/50">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Request {{ $transferRequest->request_number }}</h1>
                        @php $color = $transferRequest->getStatusBadgeColor(); @endphp
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                            @if($color == 'yellow') bg-amber-100 text-amber-700 
                            @elseif($color == 'blue') bg-blue-100 text-blue-700
                            @elseif($color == 'purple') bg-purple-100 text-purple-700
                            @elseif($color == 'green') bg-emerald-100 text-emerald-700
                            @elseif($color == 'red') bg-rose-100 text-rose-700
                            @else bg-slate-100 text-slate-600 @endif">
                            {{ str_replace('_', ' ', $transferRequest->status) }}
                        </span>
                    </div>
                    <p class="text-slate-500 font-medium">Requested on {{ $transferRequest->requested_at->format('F d, Y \a\t h:i A') }}</p>
                </div>

                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Requester</p>
                    <p class="text-lg font-bold text-slate-900 leading-none">{{ $transferRequest->requester->name }}</p>
                    <p class="text-sm text-slate-500">{{ $transferRequest->requester->role }}</p>
                </div>
            </div>
        </div>

        {{-- Summary Stats Bar (Functionality Restored) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-200 border-b border-slate-200 bg-indigo-50/30">
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Total SKUs</p>
                <p class="text-3xl font-black text-slate-900">{{ $transferRequest->getTotalProducts() }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Total Boxes</p>
                <p class="text-3xl font-black text-slate-900">{{ $transferRequest->getTotalBoxes() }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Total Individual Units</p>
                <p class="text-3xl font-black text-slate-900">
                    {{ number_format($transferRequest->transfers->sum(function($t) { return $t->quantity * $t->product->units_per_box; })) }}
                </p>
            </div>
        </div>

        {{-- Logistics Route Visualization --}}
        <div class="p-8 border-b border-slate-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative">
                <div class="hidden md:block absolute top-1/2 left-1/4 right-1/4 h-px border-t border-dashed border-slate-300"></div>
                
                <div class="w-full md:w-5/12 p-6 bg-slate-50 rounded-2xl border border-slate-200 relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dispatch Point (From)</p>
                    <p class="text-xl font-bold text-slate-900">{{ $transferRequest->fromLocation->name }}</p>
                    <p class="text-sm text-slate-500 font-medium">{{ ucfirst($transferRequest->fromLocation->type) }}</p>
                </div>

                <div class="relative z-10 w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white shadow-lg ring-4 ring-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>

                <div class="w-full md:w-5/12 p-6 bg-indigo-50/50 rounded-2xl border border-indigo-100 relative z-10">
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-2">Receiving Point (To)</p>
                    <p class="text-xl font-bold text-slate-900">{{ $transferRequest->toLocation->name }}</p>
                    <p class="text-sm text-slate-500 font-medium">{{ ucfirst($transferRequest->toLocation->type) }}</p>
                </div>
            </div>
        </div>

        {{-- Product Manifest Table (Functionality Restored) --}}
        <div class="p-0">
            <div class="px-8 py-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Inventory Shipping Manifest</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-y border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-8 py-4">Product / SKU</th>
                            <th class="px-8 py-4 text-center">Boxes</th>
                            <th class="px-8 py-4 text-center">Units/Box</th>
                            <th class="px-8 py-4 text-center">Total Units</th>
                            <th class="px-8 py-4 text-right">Item Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transferRequest->transfers as $transfer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-900">{{ $transfer->product->name }}</p>
                                <p class="text-xs text-slate-500 font-mono tracking-tighter">SKU: {{ $transfer->product_id }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-lg font-black text-indigo-600">{{ $transfer->quantity }}</span>
                            </td>
                            <td class="px-8 py-5 text-center text-sm text-slate-600 font-medium">
                                {{ $transfer->product->units_per_box }}
                            </td>
                            <td class="px-8 py-5 text-center font-bold text-slate-900">
                                {{ number_format($transfer->quantity * $transfer->product->units_per_box) }}
                            </td>
                            <td class="px-8 py-5 text-right">
                                @php $itemColor = $transfer->getStatusBadgeColor(); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase
                                    @if($itemColor == 'yellow') bg-amber-100 text-amber-700
                                    @elseif($itemColor == 'green') bg-emerald-100 text-emerald-700
                                    @elseif($itemColor == 'red') bg-rose-100 text-rose-700
                                    @else bg-slate-100 text-slate-600 @endif">
                                    {{ $transfer->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Audit Timeline & Notes Grid (Functionality Restored) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200 border-t border-slate-200 bg-slate-50/30">
            {{-- Timeline Audit --}}
            <div class="p-8">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6">Activity Audit Trail</h3>
                <div class="space-y-8 relative before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    
                    {{-- Created --}}
                    <div class="flex gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-blue-100 border-4 border-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Request Formulated</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $transferRequest->requested_at->format('M d, Y • h:i A') }}</p>
                            <p class="text-xs text-indigo-600 font-bold uppercase mt-0.5">{{ $transferRequest->requester->name }}</p>
                        </div>
                    </div>

                    {{-- Approved --}}
                    @if($transferRequest->approved_at)
                    <div class="flex gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 border-4 border-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Approved & Packed</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $transferRequest->approved_at->format('M d, Y • h:i A') }}</p>
                            <p class="text-xs text-indigo-600 font-bold uppercase mt-0.5">{{ $transferRequest->approver->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Received --}}
                    @if($transferRequest->received_at)
                    <div class="flex gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 border-4 border-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Final Receipt Confirmed</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $transferRequest->received_at->format('M d, Y • h:i A') }}</p>
                            <p class="text-xs text-emerald-600 font-bold uppercase mt-0.5">{{ $transferRequest->receiver->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Notes Section --}}
            <div class="p-8">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Official Notes</h3>
                @if($transferRequest->notes)
                    <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm text-sm text-slate-600 italic leading-relaxed">
                        "{{ $transferRequest->notes }}"
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No internal notes were logged for this request.</p>
                @endif
            </div>
        </div>

        {{-- Final Actions Footer (MethodNotAllowed Fix Applied) --}}
        <div class="p-8 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-6 print:hidden">
            <a href="{{ route('transfers.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-widest">
                ← Exit Document
            </a>
            
            <div class="flex flex-wrap justify-center gap-3 w-full sm:w-auto">
                {{-- Approve Fix: Now using a Form to allow POST --}}
                @if($transferRequest->isPending() && auth()->user()->canApproveTransfers())
                    <!-- <form method="POST" action="{{ route('transfers.approve', $transferRequest->id) }}" class="flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                            Process & Approve Request
                        </button>
                    </form> -->
                    <a href="{{ route('transfers.approve', $transferRequest->id) }}" class="flex-1 sm:flex-none">
                        Review & Approve
                    </a>
                @endif

                @if($transferRequest->isInTransit() && auth()->user()->location_id == $transferRequest->to_location_id)
                    <form method="POST" action="{{ route('transfers.receive', $transferRequest->id) }}" class="flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all active:scale-95">
                            Verify & Receive Manifest
                        </button>
                    </form>
                @endif

                @if(!$transferRequest->isCompleted() && (auth()->user()->isOwner() || $transferRequest->requested_by == auth()->user()->id))
                    <form method="POST" action="{{ route('transfers.cancel', $transferRequest->id) }}" 
                          onsubmit="return confirm('Void this entire request? This action cannot be reversed.')">
                        @csrf
                        <button type="submit" class="px-6 py-3 text-rose-500 hover:text-rose-700 text-sm font-bold transition-colors">
                            Void Request
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection