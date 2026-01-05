@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8 p-4 md:p-8">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Stock Transfer Requests</h1>
            <p class="text-slate-500 mt-1">Manage and track bulk inventory requests across all locations.</p>
        </div>
        
        @if(auth()->user()->canRequestTransfers())
            <a href="{{ route('transfers.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Request
            </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach(['pending', 'approved', 'in_transit', 'completed'] as $status)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <p class="text-sm font-medium text-slate-500 capitalize mb-2">{{ str_replace('_', ' ', $status) }}</p>
                <h3 class="text-3xl font-bold text-slate-900">{{ $stats[$status] ?? 0 }}</h3>
            </div>
        @endforeach
    </div>

    {{-- Transfer Requests Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    <th class="px-6 py-4">Request #</th>
                    <th class="px-6 py-4">Requester</th>
                    <th class="px-6 py-4">Route</th>
                    <th class="px-6 py-4">Products</th>
                    <th class="px-6 py-4">Total Boxes</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Timeline</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transferRequests as $transferRequest)
                {{-- Refactored: Added clickable-row class and data-href to avoid JS Linting errors --}}
                <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer clickable-row" 
                    data-href="{{ route('transfers.show', $transferRequest->id) }}">
                    
                    {{-- Request Number --}}
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-bold text-indigo-600">{{ $transferRequest->request_number }}</span>
                    </td>

                    {{-- Requester --}}
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $transferRequest->requester->name }}</div>
                        <div class="text-xs text-slate-500">{{ $transferRequest->requester->role }}</div>
                    </td>

                    {{-- Route --}}
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-600">{{ $transferRequest->fromLocation->name }}</span>
                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <span class="text-indigo-600 font-semibold">{{ $transferRequest->toLocation->name }}</span>
                        </div>
                    </td>

                    {{-- Products Count --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm">
                                {{ $transferRequest->getTotalProducts() }}
                            </span>
                            <span class="text-xs text-slate-500">SKUs</span>
                        </div>
                    </td>

                    {{-- Total Boxes --}}
                    <td class="px-6 py-4">
                        <span class="text-lg font-bold text-slate-900">{{ $transferRequest->getTotalBoxes() }}</span>
                        <span class="text-xs text-slate-400 uppercase ml-1">boxes</span>
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-6 py-4">
                        @php $color = $transferRequest->getStatusBadgeColor(); @endphp
                        <span class="px-2.5 py-1 rounded-md border text-[10px] font-bold uppercase tracking-wider 
                            @if($color == 'yellow') bg-amber-50 text-amber-700 border-amber-100 
                            @elseif($color == 'blue') bg-blue-50 text-blue-700 border-blue-100
                            @elseif($color == 'purple') bg-purple-50 text-purple-700 border-purple-100
                            @elseif($color == 'green') bg-emerald-50 text-emerald-700 border-emerald-100
                            @elseif($color == 'red') bg-rose-50 text-rose-700 border-rose-100
                            @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                            {{ str_replace('_', ' ', $transferRequest->status) }}
                        </span>
                    </td>

                    {{-- Timeline --}}
                    <td class="px-6 py-4 text-xs text-slate-500">
                        <div class="font-bold text-slate-700">{{ $transferRequest->requested_at->format('M d, Y') }}</div>
                        <div>{{ $transferRequest->requested_at->format('h:i A') }}</div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                        <div class="flex justify-end gap-2">
                            @if($transferRequest->isPending() && auth()->user()->canApproveTransfers())
                                <!-- <a href="{{ route('transfers.approve', $transferRequest->id) }}"
                                   class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition">
                                    Review & Approve
                                </a> -->

                                <a href="{{ route('transfers.show', $transferRequest->id) }}"
                                    class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition">
                                 Review & Approve
</a>



                            @endif

                            @if($transferRequest->isInTransit() && auth()->user()->location_id == $transferRequest->to_location_id)
                                <form method="POST" action="{{ route('transfers.receive', $transferRequest->id) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-700 shadow-sm transition">
                                        Confirm Receipt
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('transfers.show', $transferRequest->id) }}" 
                               class="p-2 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 text-lg font-semibold mb-2">No transfer requests found</p>
                            @if(auth()->user()->canRequestTransfers())
                                <a href="{{ route('transfers.create') }}" class="text-indigo-600 font-bold hover:underline">
                                    Create your first request →
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($transferRequests->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transferRequests->links() }}
            </div>
        @endif
    </div>
</div>

{{-- This Script handles the row clicking and fixes the VS Code Error --}}
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