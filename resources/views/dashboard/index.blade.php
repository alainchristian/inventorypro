{{-- FILE: resources/views/dashboard/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen  pb-12" x-data="{ activeTab: 'overview' }">
    
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-8 pt-8">
        
        {{-- Enhanced Header with Gradient --}}
        <!-- <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-2xl shadow-xl">
            <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:20px_20px]"></div>
            <div class="relative px-8 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-black text-white tracking-tight">Operations Dashboard</h1>
                        <p class="text-blue-100 mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $startDate }} to {{ $endDate }}
                        </p>
                    </div>
                    
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div>
                            <label class="block text-xs font-bold text-white/90 uppercase mb-2 tracking-wider">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" 
                                   class="rounded-lg border-0 bg-white/95 text-sm focus:ring-2 focus:ring-white shadow-lg px-4 py-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-white/90 uppercase mb-2 tracking-wider">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" 
                                   class="rounded-lg border-0 bg-white/95 text-sm focus:ring-2 focus:ring-white shadow-lg px-4 py-2.5">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-white text-blue-700 px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Apply Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="bg-white/20 backdrop-blur text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-white/30 transition border border-white/30">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <!-- Optional: Thin top accent bar for "Operations" branding -->
    <div class="h-1 bg-blue-600"></div>
    
    <div class="px-6 py-5">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            
            <!-- Branding & Title -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-slate-900 leading-none">Operations Dashboard</h1>
                    <div class="flex items-center gap-1.5 mt-1.5 text-xs font-medium text-slate-500">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                        Live Reports: {{ $startDate }} — {{ $endDate }}
                    </div>
                </div>
            </div>

            <!-- Functional Filter Group -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg px-2 divide-x divide-slate-200">
                    <!-- Start Date -->
                    <div class="flex items-center gap-2 px-2 py-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">From</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="bg-transparent border-0 p-0 text-sm text-slate-700 focus:ring-0 cursor-pointer">
                    </div>
                    <!-- End Date -->
                    <div class="flex items-center gap-2 px-3 py-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">To</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="bg-transparent border-0 p-0 text-sm text-slate-700 focus:ring-0 cursor-pointer">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 ml-2">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-md transition-all">
                        Reset
                    </a>
                    <button type="submit" 
                            class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-800 transition-shadow shadow-sm active:scale-[0.98]">
                        Update View
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
        

        {{-- Premium Stats Cards with Hover Effects --}}
        <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">ACTIVE</span>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Locations</p>
                        <p class="text-4xl font-black text-gray-900 mt-2">{{ $stats['total_locations'] }}</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-yellow-200">
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">REVIEW</span>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending Requests</p>
                        <p class="text-4xl font-black text-yellow-600 mt-2">{{ $stats['pending_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">ALERT</span>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Low Stock Items</p>
                        <p class="text-4xl font-black text-red-600 mt-2">{{ $stats['low_stock_items'] }}</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-orange-200">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">ACTION</span>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Damaged Units</p>
                        <p class="text-4xl font-black text-orange-600 mt-2">{{ $stats['total_damaged_units'] }}</p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Total Locations -->
    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">Total Locations</p>
                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">ACTIVE</span>
                </div>
                <p class="text-2xl font-black text-gray-900 leading-tight">{{ $stats['total_locations'] }}</p>
            </div>
        </div>
    </div>

    <!-- Card 2: Pending Requests -->
    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-yellow-200">
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">Pending Requests</p>
                    <span class="text-[9px] font-black text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full">REVIEW</span>
                </div>
                <p class="text-2xl font-black text-yellow-600 leading-tight">{{ $stats['pending_requests'] }}</p>
            </div>
        </div>
    </div>

    <!-- Card 3: Low Stock Items -->
    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">Low Stock Items</p>
                    <span class="text-[9px] font-black text-red-600 bg-red-50 px-2 py-0.5 rounded-full">ALERT</span>
                </div>
                <p class="text-2xl font-black text-red-600 leading-tight">{{ $stats['low_stock_items'] }}</p>
            </div>
        </div>
    </div>

    <!-- Card 4: Damaged Units -->
    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-orange-200">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">Damaged Units</p>
                    <span class="text-[9px] font-black text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">ACTION</span>
                </div>
                <p class="text-2xl font-black text-orange-600 leading-tight">{{ $stats['total_damaged_units'] }}</p>
            </div>
        </div>
    </div>
</div>

        {{-- Premium Tab Navigation --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <nav class="flex border-b border-gray-100" aria-label="Tabs">
                <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="flex-1 py-5 px-6 text-sm font-bold uppercase tracking-wider transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Sales Analysis
                    </div>
                </button>
                <button @click="activeTab = 'inventory'" 
                    :class="activeTab === 'inventory' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="flex-1 py-5 px-6 text-sm font-bold uppercase tracking-wider transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Inventory Status
                    </div>
                </button>
                <button @click="activeTab = 'alerts'" 
                    :class="activeTab === 'alerts' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="flex-1 py-5 px-6 text-sm font-bold uppercase tracking-wider transition-all relative">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Alerts & Requests
                        @if($stats['pending_requests'] > 0 || $damagedItems['total_damaged_units'] > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-black px-2.5  rounded-full shadow-lg animate-pulse">
                                {{ $stats['pending_requests'] + ($damagedItems['total_damaged_units'] > 0 ? 1 : 0) }}
                            </span>
                        @endif
                    </div>
                </button>
            </nav>

            {{-- TAB CONTENT --}}
            <div class="p-8">
                {{-- TAB 1: SALES ANALYTICS --}}
                <div x-show="activeTab === 'overview'" x-transition class="space-y-8">
                    
                    {{-- Executive Summary --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-8 text-white">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative">
                                <p class="text-sm font-bold uppercase tracking-widest opacity-90">Total Revenue</p>
                                <div class="flex items-baseline gap-3 mt-4">
                                    <h3 class="text-5xl font-black">{{ number_format($salesData['total_sales']) }}</h3>
                                    <span class="text-xl font-bold opacity-75">RWF</span>
                                </div>
                                <div class="mt-6 flex items-center text-sm font-bold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    Gross sales for period
                                </div>
                            </div>
                        </div>

                        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-8 text-white">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative">
                                <p class="text-sm font-bold uppercase tracking-widest opacity-90">Volume Sold</p>
                                <div class="flex items-baseline gap-3 mt-4">
                                    <h3 class="text-5xl font-black">{{ number_format($salesData['total_boxes']) }}</h3>
                                    <span class="text-xl font-bold opacity-75">Boxes</span>
                                </div>
                                <div class="mt-6 w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                    <div class="bg-white h-2 rounded-full shadow-lg" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-8 text-white">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative">
                                <p class="text-sm font-bold uppercase tracking-widest opacity-90">Avg. per Location</p>
                                <div class="flex items-baseline gap-3 mt-4">
                                    <h3 class="text-5xl font-black">
                                        {{ number_format($salesData['total_sales'] / max(count($salesData['by_location']), 1)) }}
                                    </h3>
                                    <span class="text-xl font-bold opacity-75">RWF</span>
                                </div>
                                <p class="mt-6 text-sm font-bold opacity-90">Across {{ count($salesData['by_location']) }} locations</p>
                            </div>
                        </div>
                    </div>

                    {{-- Location Performance Leaderboard --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-600 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900">Location Performance Leaderboard</h3>
                            </div>
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Sorted by Revenue</span>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                @foreach(collect($salesData['by_location'])->sortByDesc('total_amount') as $locationSales)
                                <div class="group flex items-center bg-gradient-to-r from-gray-50 to-white hover:from-blue-50 hover:to-blue-100 rounded-xl p-5 transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:shadow-lg">
                                    <div class="flex items-center justify-center w-14 h-14 mr-5 text-3xl font-black text-gray-200 group-hover:text-blue-400 transition-colors bg-white rounded-xl shadow-sm">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-2">
                                            <span class="font-black text-gray-900 text-lg">{{ $locationSales['location_name'] }}</span>
                                            <span class="font-black text-blue-600 text-lg">{{ number_format($locationSales['total_amount']) }} RWF</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                                            @php 
                                                $percentage = ($locationSales['total_amount'] / max($salesData['total_sales'], 1)) * 100;
                                            @endphp
                                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-700 shadow-lg" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-2 text-xs text-gray-500 uppercase font-black tracking-tight">
                                            <span>{{ $locationSales['total_boxes'] }} BOXES</span>
                                            <span>{{ number_format($percentage, 1) }}% SHARE</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Product Performance Table --}}
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-purple-50 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-600 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900">Product Performance Analysis</h3>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-widest">Product</th>
                                        <th class="px-8 py-4 text-center text-xs font-black text-gray-600 uppercase tracking-widest">Volume</th>
                                        <th class="px-8 py-4 text-right text-xs font-black text-gray-600 uppercase tracking-widest">Revenue</th>
                                        <th class="px-8 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-widest">Distribution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($salesData['by_product'] as $productSales)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all">
                                        <td class="px-8 py-5">
                                            <div class="font-black text-gray-900 text-base">{{ $productSales['product_name'] }}</div>
                                            <div class="text-xs text-gray-400 font-mono font-bold mt-1">SKU: {{ $productSales['product_id'] }}</div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-black bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 shadow-sm">
                                                {{ $productSales['total_boxes'] }} Boxes
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="text-lg font-black text-gray-900">{{ number_format($productSales['total_amount']) }}</div>
                                            <div class="text-xs text-green-600 font-black">RWF</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2 w-56 h-3 rounded-full overflow-hidden bg-gray-100 shadow-inner">
                                                @foreach($productSales['locations'] as $loc)
                                                    @php 
                                                        $locShare = ($loc['amount'] / max($productSales['total_amount'], 1)) * 100;
                                                        $colors = ['bg-gradient-to-r from-blue-500 to-blue-600', 'bg-gradient-to-r from-indigo-500 to-indigo-600', 'bg-gradient-to-r from-purple-500 to-purple-600', 'bg-gradient-to-r from-cyan-500 to-cyan-600'];
                                                        $color = $colors[$loop->index % count($colors)];
                                                    @endphp
                                                    <div class="{{ $color }} h-full transition-all duration-500" 
                                                         style="width: {{ $locShare }}%" 
                                                         title="{{ $loc['location_name'] }}: {{ number_format($locShare, 1) }}%">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach($productSales['locations'] as $loc)
                                                    <span class="text-xs font-black text-gray-500 uppercase">
                                                        {{ Str::limit($loc['location_name'], 3, '') }}: {{ $loc['boxes_sold'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: INVENTORY STATUS --}}
                <div x-show="activeTab === 'inventory'" x-transition class="space-y-6">
                    @foreach($inventoryData['by_location'] as $locationInv)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" x-data="{ open: true }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-8 py-6 bg-gradient-to-r from-gray-50 to-blue-50 hover:from-gray-100 hover:to-blue-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white text-2xl shadow-lg">
                                    {{ $locationInv['location_type'] === 'warehouse' ? '🏭' : '🏪' }}
                                </div>
                                <div class="text-left">
                                    <h4 class="font-black text-gray-900 text-lg">{{ $locationInv['location_name'] }}</h4>
                                    <p class="text-xs text-gray-500 font-bold mt-1">
                                        {{ $locationInv['unique_products'] }} Products • {{ $locationInv['total_boxes'] }} Boxes
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                @if($locationInv['low_stock_count'] > 0)
                                    <span class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-4 py-2 rounded-full font-black shadow-lg">
                                        ⚠️ {{ $locationInv['low_stock_count'] }} Low Stock
                                    </span>
                                @endif
                                <svg class="w-6 h-6 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>
                        
                        <div x-show="open" x-transition class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="text-left border-b-2 border-gray-200">
                                            <th class="pb-4 pl-4 font-black uppercase text-xs text-gray-600 tracking-widest">Product</th>
                                            <th class="pb-4 font-black uppercase text-xs text-gray-600 tracking-widest">Boxes</th>
                                            <th class="pb-4 font-black uppercase text-xs text-gray-600 tracking-widest">Loose Units</th>
                                            <th class="pb-4 text-right pr-4 font-black uppercase text-xs text-gray-600 tracking-widest">Total Units</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($locationInv['products'] as $product)
                                        <tr class="hover:bg-blue-50/50 transition-colors {{ $product['is_low_stock'] ? 'bg-red-50' : '' }}">
                                            <td class="py-4 pl-4">
                                                <div class="font-bold text-gray-900">{{ $product['product_name'] }}</div>
                                                @if($product['is_low_stock']) 
                                                    <span class="inline-flex items-center gap-1 text-xs text-red-600 font-black uppercase bg-red-100 px-2 py-0.5 rounded-full mt-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                        Low Stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                <span class="font-black text-lg {{ $product['is_low_stock'] ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $product['boxes'] }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <span class="font-semibold text-gray-600">{{ $product['loose_units'] }}</span>
                                            </td>
                                            <td class="py-4 text-right pr-4">
                                                <span class="font-black text-lg text-blue-600">{{ $product['total_units'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- TAB 3: ALERTS & REQUESTS --}}
                <div x-show="activeTab === 'alerts'" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- Damaged Items --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-orange-900">Damaged Inventory</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                            @forelse($damagedItems['inventory_damaged'] as $locationDamaged)
                                <div class="border-2 border-orange-100 rounded-xl p-5 bg-gradient-to-br from-orange-50/50 to-white hover:shadow-lg transition-all">
                                    <h4 class="font-black text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $locationDamaged['location_name'] }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($locationDamaged['products'] as $product)
                                            <div class="flex justify-between items-center bg-white rounded-lg p-3 border border-orange-100">
                                                <span class="text-sm font-bold text-gray-700">{{ $product['product_name'] }}</span>
                                                <span class="font-black text-orange-600 text-lg">{{ $product['damaged_units'] }} units</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-bold">No damaged items reported</p>
                                    <p class="text-xs text-gray-400 mt-1">All inventory is in good condition</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Pending Requests --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-yellow-50 to-amber-50 border-b border-yellow-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-yellow-900">Pending Transfers</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                            @forelse($pendingRequests as $request)
                                <div class="border-2 border-yellow-200 bg-gradient-to-br from-yellow-50/50 to-white rounded-xl p-5 hover:shadow-lg transition-all">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-1">
                                            <span class="inline-flex items-center text-xs font-black uppercase tracking-widest text-yellow-800 bg-yellow-200 px-3 py-1.5 rounded-full shadow-sm">
                                                {{ str_replace('_', ' ', $request->status) }}
                                            </span>
                                            <h4 class="font-black text-gray-900 text-lg mt-3">{{ $request->product->name }}</h4>
                                            <div class="flex items-center gap-2 mt-2 text-sm text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                <span class="font-bold">{{ $request->fromLocation->name }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                                <span class="font-bold">{{ $request->toLocation->name }}</span>
                                            </div>
                                            <div class="mt-3 inline-flex items-center gap-2 bg-blue-100 px-3 py-1.5 rounded-lg">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                <span class="text-sm font-black text-blue-900">{{ $request->quantity }} Boxes</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            @if($request->status === 'pending' && auth()->user()->canApproveTransfers())
                                                <form method="POST" action="{{ route('transfers.approve', $request->id) }}">
                                                    @csrf
                                                    <button class="bg-gradient-to-r from-green-600 to-emerald-600 text-white text-xs uppercase font-black px-5 py-3 rounded-lg hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                                        ✓ Approve
                                                    </button>
                                                </form>
                                            @endif
                                            @if($request->status === 'in_transit' && $request->to_location_id === auth()->user()->location_id)
                                                <form method="POST" action="{{ route('transfers.receive', $request->id) }}">
                                                    @csrf
                                                    <button class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs uppercase font-black px-5 py-3 rounded-lg hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                                        📦 Receive
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-bold">No pending requests</p>
                                    <p class="text-xs text-gray-400 mt-1">All transfers are up to date</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

.bg-grid-white\/\[0\.05\] {
    background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection