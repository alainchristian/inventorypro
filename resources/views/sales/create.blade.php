@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-12" x-data="saleForm()">

    {{-- Top Navigation --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('sales.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <nav class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-tight mb-0.5">
                            <span>Sales</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-emerald-600">New Sale</span>
                        </nav>
                        <h1 class="text-lg font-extrabold text-slate-900 leading-none tracking-tight">Record Sale Transaction</h1>
                    </div>
                </div>

                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/50 tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    Recording
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-red-800">Please fix the following errors:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Location & Product Selection --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-emerald-600 rounded-full"></div>
                    Sale Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Location Selection --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <select name="location_id"
                                x-model="selectedLocation"
                                @change="updateInventory()"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $selectedLocation) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Product Selection --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Product <span class="text-red-500">*</span>
                        </label>
                        <select name="product_id"
                                x-model="selectedProduct"
                                @change="updateProductDetails()"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                        data-price="{{ $product->selling_price }}"
                                        data-units-per-box="{{ $product->units_per_box }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Stock Availability Display --}}
                <div x-show="selectedProduct && selectedLocation"
                     class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Available Boxes</p>
                            <p class="text-2xl font-black text-blue-900" x-text="availableBoxes"></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Available Loose Units</p>
                            <p class="text-2xl font-black text-blue-900" x-text="availableLooseUnits"></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Units Per Box</p>
                            <p class="text-2xl font-black text-blue-900" x-text="unitsPerBox"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quantity & Pricing --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-emerald-600 rounded-full"></div>
                    Quantity & Pricing
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Unit Type --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Unit Type <span class="text-red-500">*</span>
                        </label>
                        <select name="unit_type"
                                x-model="unitType"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold">
                            <option value="">Select Type</option>
                            <option value="box" {{ old('unit_type') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="loose" {{ old('unit_type') == 'loose' ? 'selected' : '' }}>Loose Units</option>
                        </select>
                        @error('unit_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="quantity"
                               x-model="quantity"
                               @input="calculateTotal()"
                               min="1"
                               step="1"
                               required
                               value="{{ old('quantity') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold"
                               placeholder="Enter quantity">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unit Price --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Unit Price (₦) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="unit_price"
                               x-model="unitPrice"
                               @input="calculateTotal()"
                               min="0"
                               step="0.01"
                               required
                               value="{{ old('unit_price') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold"
                               placeholder="0.00">
                        @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Total Amount Display --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-emerald-700 uppercase tracking-wider">Total Amount</span>
                        <span class="text-3xl font-black text-emerald-900">
                            ₦<span x-text="totalAmount.toFixed(2)">0.00</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Customer Information (Optional) --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-slate-400 rounded-full"></div>
                    Customer Information (Optional)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Customer Name --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Customer Name</label>
                        <input type="text"
                               name="customer_name"
                               value="{{ old('customer_name') }}"
                               maxlength="100"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                               placeholder="Enter customer name">
                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Customer Phone --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Customer Phone</label>
                        <input type="text"
                               name="customer_phone"
                               value="{{ old('customer_phone') }}"
                               maxlength="20"
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                               placeholder="Enter phone number">
                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                    <textarea name="notes"
                              rows="3"
                              maxlength="500"
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                              placeholder="Any additional notes about this sale...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Complete Sale
                </button>
                <a href="{{ route('sales.index') }}"
                   class="px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </main>
</div>

<script>
function saleForm() {
    return {
        selectedLocation: '{{ old('location_id', $selectedLocation ?? '') }}',
        selectedProduct: '{{ old('product_id') }}',
        unitType: '{{ old('unit_type') }}',
        quantity: {{ old('quantity', 0) }},
        unitPrice: {{ old('unit_price', 0) }},
        totalAmount: 0,

        // Inventory data
        availableBoxes: 0,
        availableLooseUnits: 0,
        unitsPerBox: 0,

        // Inventory from server
        inventory: @js($inventory),

        init() {
            this.updateInventory();
            this.updateProductDetails();
            this.calculateTotal();
        },

        updateInventory() {
            if (!this.selectedLocation || !this.selectedProduct) {
                this.availableBoxes = 0;
                this.availableLooseUnits = 0;
                return;
            }

            const item = this.inventory.find(i =>
                i.location_id == this.selectedLocation &&
                i.product_id == this.selectedProduct
            );

            if (item) {
                this.availableBoxes = item.boxes;
                this.availableLooseUnits = item.loose_units;
            } else {
                this.availableBoxes = 0;
                this.availableLooseUnits = 0;
            }
        },

        updateProductDetails() {
            if (!this.selectedProduct) {
                this.unitPrice = 0;
                this.unitsPerBox = 0;
                return;
            }

            const productSelect = document.querySelector('select[name="product_id"]');
            const selectedOption = productSelect.options[productSelect.selectedIndex];

            if (selectedOption) {
                this.unitPrice = parseFloat(selectedOption.dataset.price || 0);
                this.unitsPerBox = parseInt(selectedOption.dataset.unitsPerBox || 0);
            }

            this.updateInventory();
            this.calculateTotal();
        },

        calculateTotal() {
            const qty = parseFloat(this.quantity) || 0;
            const price = parseFloat(this.unitPrice) || 0;
            this.totalAmount = qty * price;
        }
    }
}
</script>
@endsection
