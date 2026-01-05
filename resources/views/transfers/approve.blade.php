@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-4 md:p-8">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-8 border-b bg-gradient-to-r from-indigo-50 to-purple-50">
            <h1 class="text-2xl font-bold">Review & Approve Request</h1>
            <p class="text-slate-600 mt-1">{{ $transferRequest->request_number }} - {{ $transferRequest->getTotalProducts() }} products</p>
        </div>

        <form method="POST" action="{{ route('transfers.process-approval', $transferRequest->id) }}" class="p-8">
        
            @csrf
            
            <table class="w-full">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Requested</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Available</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Approve Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transferRequest->transfers as $transfer)
                    @php
                        $inventory = \App\Models\Inventory::where('location_id', $transfer->from_location_id)
                            ->where('product_id', $transfer->product_id)
                            ->first();
                        $available = $inventory ? $inventory->boxes : 0;
                    @endphp
                    <tr>
                        <td class="px-4 py-4">
                            <div class="font-bold">{{ $transfer->product->name }}</div>
                            <div class="text-xs text-slate-500">{{ $transfer->product_id }}</div>
                        </td>
                        <td class="px-4 py-4 text-center font-bold">{{ $transfer->quantity }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-semibold {{ $available < $transfer->quantity ? 'text-red-600' : 'text-green-600' }}">
                                {{ $available }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <input type="number" 
                                   name="quantities[{{ $transfer->id }}]" 
                                   value="{{ min($transfer->quantity, $available) }}"
                                   min="0"
                                   max="{{ $available }}"
                                   class="w-24 text-center rounded-lg border-slate-300 font-bold">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-8 flex justify-between items-center pt-6 border-t">
                <a href="{{ route('transfers.index') }}" class="text-slate-600 hover:text-slate-900 font-semibold">
                    ← Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg">
                    Approve & Pack Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection