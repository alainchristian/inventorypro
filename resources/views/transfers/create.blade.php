{{-- FILE: resources/views/transfers/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-12" x-data="transferRequestForm(@js($warehouseInventory))">
    
    {{-- Refined Top Navigation --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('transfers.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <nav class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-tight mb-0.5">
                            <span>Inventory</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-indigo-600">New Transfer</span>
                        </nav>
                        <h1 class="text-lg font-extrabold text-slate-900 leading-none tracking-tight">Create Stock Request</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end border-r border-slate-200 pr-4 mr-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Source Warehouse</span>
                        <span class="text-sm font-bold text-slate-700">{{ $warehouse->name }}</span>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200/50 tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                        Drafting
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Product Selection & Table --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Search Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                            <div class="w-1.5 h-4 bg-indigo-600 rounded-full"></div>
                            Product Selection
                        </h2>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="filteredInventory.length + ' SKUs available'"></span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="relative flex-1" @click.away="isOpen = false">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" 
                                       class="w-full bg-slate-50 border-slate-200 rounded-lg py-3 pl-10 pr-10 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all text-sm font-semibold text-slate-900"
                                       placeholder="Search product name or SKU..."
                                       x-model="searchTerm"
                                       @focus="isOpen = true"
                                       @input="isOpen = true; selectedProductInfo = null">
                            </div>

                            {{-- Taller Dropdown Menu --}}
                            <div x-show="isOpen" 
                                 class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                
                                <div class="max-h-[450px] overflow-y-auto divide-y divide-slate-50">
                                    <template x-for="item in filteredInventory" :key="item.product_id">
                                        <button type="button" 
                                                @click="selectProduct(item)"
                                                class="w-full px-4 py-2.5 text-left hover:bg-indigo-50/50 transition-all flex justify-between items-center group">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-800 group-hover:text-indigo-600" x-text="item.product_name"></span>
                                                <span class="text-[10px] font-mono text-slate-400" x-text="'SKU: ' + item.product_id"></span>
                                            </div>
                                            <div class="bg-slate-100 group-hover:bg-white px-2 py-1 rounded-md text-right border border-transparent group-hover:border-indigo-100 transition-all">
                                                <div class="text-[11px] font-black text-slate-700" x-text="item.available_boxes"></div>
                                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter leading-none">In Stock</div>
                                            </div>
                                        </button>
                                    </template>
                                    <div x-show="filteredInventory.length === 0" class="px-5 py-12 text-center">
                                        <p class="text-slate-400 text-sm font-medium">No matches for "<span x-text="searchTerm" class="text-slate-900 font-bold"></span>"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <div class="w-24 md:w-32">
                                <input type="number" x-model="quantity" min="1"
                                       class="w-full bg-slate-50 border-slate-200 rounded-lg py-3 px-3 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-black text-sm text-center"
                                       placeholder="Qty">
                            </div>

                            <button type="button" @click="addProduct()" :disabled="!selectedProductInfo"
                                    class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold text-sm hover:bg-indigo-700 disabled:bg-slate-100 disabled:text-slate-400 transition-all shadow-md active:scale-95 flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Item
                            </button>
                        </div>
                    </div>

                    <div class="h-4 mt-3">
                        <p x-show="validationMessage" 
                           x-transition x-text="validationMessage" 
                           :class="validationError ? 'text-rose-600' : 'text-emerald-600'"
                           class="text-[10px] font-black uppercase tracking-[0.15em]"></p>
                    </div>
                </div>

                {{-- Table Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Quantity</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Units</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-if="selectedProducts.length === 0">
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="opacity-20 mb-3 grayscale flex justify-center">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Request list is empty</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(product, index) in selectedProducts" :key="product.product_id">
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-slate-900" x-text="product.product_name"></div>
                                        <div class="text-[10px] font-mono font-bold text-slate-400 mt-0.5" x-text="product.product_id"></div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center gap-1 bg-white border border-slate-200 rounded-lg p-1 w-fit mx-auto shadow-sm">
                                            <button type="button" @click="decreaseQuantity(index)" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded transition-colors">-</button>
                                            <span class="w-10 text-center text-sm font-black text-slate-900" x-text="product.quantity"></span>
                                            <button type="button" @click="increaseQuantity(index)" :disabled="product.quantity >= product.available_boxes" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded disabled:opacity-20 transition-colors">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="inline-flex flex-col">
                                            <span class="text-sm font-black text-indigo-600" x-text="(product.quantity * product.units_per_box).toLocaleString()"></span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Total Pcs</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button type="button" @click="removeProduct(index)" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <aside class="lg:col-span-4">
                <form method="POST" action="{{ route('transfers.store') }}">
                    @csrf
                    
                    {{-- Hidden Dynamic Inputs --}}
                    <template x-for="(product, index) in selectedProducts" :key="index">
                        <div>
                            <input type="hidden" :name="'products[' + index + '][product_id]'" :value="product.product_id">
                            <input type="hidden" :name="'products[' + index + '][quantity]'" :value="product.quantity">
                        </div>
                    </template>

                    <div class="bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 p-8 sticky top-24">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-4 border-b border-slate-100">Order Summary</h3>
                        
                        <div class="space-y-5 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">Unique SKUs</span>
                                <span class="text-sm font-black text-slate-900 bg-slate-100 px-2 py-0.5 rounded" x-text="selectedProducts.length"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">Total Boxes</span>
                                <span class="text-sm font-black text-slate-900" x-text="getTotalBoxes()"></span>
                            </div>
                            
                            <div class="pt-6 mt-6 border-t border-slate-100">
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Total Unit Quantity</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-slate-900 tracking-tighter" x-text="getTotalUnits().toLocaleString()"></span>
                                    <span class="text-xs font-bold text-slate-400 uppercase">Pieces</span>
                                </div>
                            </div>

                            <div class="pt-6">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Shipment Notes</label>
                                <textarea name="notes" rows="4" 
                                          class="w-full rounded-lg border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all p-4 text-sm font-medium placeholder:text-slate-300 resize-none shadow-inner" 
                                          placeholder="Enter delivery instructions or special handling..."></textarea>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="selectedProducts.length === 0"
                                class="w-full bg-slate-900 text-white py-4 rounded-xl font-black text-sm hover:bg-indigo-600 transition-all active:scale-[0.97] disabled:bg-slate-200 disabled:cursor-not-allowed shadow-lg shadow-indigo-100 uppercase tracking-widest">
                            Review & Submit
                        </button>
                        
                        <div class="mt-6 flex flex-col items-center gap-2">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                Requester: {{ auth()->user()->name }}
                            </p>
                        </div>
                    </div>
                </form>
            </aside>
        </div>
    </main>
</div>

<script>
function transferRequestForm(inventory) {
    return {
        allInventory: inventory,
        searchTerm: '',
        isOpen: false,
        selectedProductInfo: null,
        quantity: 1,
        selectedProducts: [],
        validationMessage: '',
        validationError: false,

        get filteredInventory() {
            const term = this.searchTerm.toLowerCase();
            if (term === '') return this.allInventory.slice(0, 20); // Show more by default
            return this.allInventory.filter(item => 
                item.product_name.toLowerCase().includes(term) || 
                item.product_id.toString().toLowerCase().includes(term)
            );
        },

        selectProduct(item) {
            this.selectedProductInfo = item;
            this.searchTerm = item.product_name;
            this.isOpen = false;
            this.validationMessage = '';
        },

        addProduct() {
            if (!this.selectedProductInfo) return;
            const qty = parseInt(this.quantity);
            
            if (qty <= 0 || isNaN(qty)) {
                this.showFeedback('Invalid quantity', true);
                return;
            }
            if (qty > this.selectedProductInfo.available_boxes) {
                this.showFeedback('Insufficient stock', true);
                return;
            }
            if (this.selectedProducts.find(p => p.product_id === this.selectedProductInfo.product_id)) {
                this.showFeedback('Already in list', true);
                return;
            }

            this.selectedProducts.push({...this.selectedProductInfo, quantity: qty});
            this.searchTerm = '';
            this.selectedProductInfo = null;
            this.quantity = 1;
            this.showFeedback('Item added', false);
        },

        showFeedback(msg, isError) {
            this.validationMessage = msg;
            this.validationError = isError;
            setTimeout(() => this.validationMessage = '', 3000);
        },

        removeProduct(index) { this.selectedProducts.splice(index, 1); },
        increaseQuantity(index) {
            const p = this.selectedProducts[index];
            if (p.quantity < p.available_boxes) p.quantity++;
        },
        decreaseQuantity(index) {
            if (this.selectedProducts[index].quantity > 1) this.selectedProducts[index].quantity--;
        },
        getTotalBoxes() { return this.selectedProducts.reduce((sum, p) => sum + p.quantity, 0); },
        getTotalUnits() { return this.selectedProducts.reduce((sum, p) => sum + (p.quantity * p.units_per_box), 0); }
    }
}
</script>
@endsection