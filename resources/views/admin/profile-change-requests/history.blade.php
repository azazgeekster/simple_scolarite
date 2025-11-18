@extends('admin.layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Change History</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        View all approved and rejected profile change requests
                    </p>
                </div>
                <a href="{{ route('admin.profile-change-requests.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors dark:focus:ring-offset-gray-900">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Pending
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.profile-change-requests.history', ['status' => 'all']) }}"
                        class="@if(request('status', 'all') === 'all') border-teal-500 text-teal-600 dark:text-teal-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        All History
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium @if(request('status', 'all') === 'all') bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400 @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400 @endif">
                            {{ $totalCount }}
                        </span>
                    </a>
                    <a href="{{ route('admin.profile-change-requests.history', ['status' => 'approved']) }}"
                        class="@if(request('status') === 'approved') border-green-500 text-green-600 dark:text-green-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Approved
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium @if(request('status') === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400 @endif">
                            {{ $approvedCount }}
                        </span>
                    </a>
                    <a href="{{ route('admin.profile-change-requests.history', ['status' => 'rejected']) }}"
                        class="@if(request('status') === 'rejected') border-red-500 text-red-600 dark:text-red-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Rejected
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium @if(request('status') === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400 @endif">
                            {{ $rejectedCount }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <form method="GET" action="{{ route('admin.profile-change-requests.history') }}" class="flex gap-2">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by student name, CNE, or field..."
                            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors dark:focus:ring-offset-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Table -->
            @if($changeRequests->isEmpty())
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No history found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No processed change requests match your criteria.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Student
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Field Changed
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Old Value
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        New Value
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Reviewed By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($changeRequests as $request)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center">
                                                    <span class="text-teal-700 dark:text-teal-400 font-semibold text-sm">
                                                        {{ substr($request->student->prenom, 0, 1) }}{{ substr($request->student->nom, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $request->student->prenom }} {{ $request->student->nom }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $request->student->cne ?? $request->student->apogee }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white font-medium">
                                                {{ $request->field_label }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $request->field_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $request->old_value }}">
                                                {{ $request->old_value ?: '(empty)' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white font-medium max-w-xs truncate" title="{{ $request->new_value }}">
                                                {{ $request->new_value }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->reviewer)
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $request->reviewer->name }}
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-600">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $request->reviewed_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $request->reviewed_at->format('H:i') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @if($request->status === 'rejected' && $request->rejection_reason)
                                        <tr class="bg-red-50 dark:bg-red-900/10">
                                            <td colspan="7" class="px-6 py-3">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 text-red-400 dark:text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-red-800 dark:text-red-400">Rejection Reason:</p>
                                                        <p class="text-sm text-red-700 dark:text-red-500 mt-1">{{ $request->rejection_reason }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($changeRequests->hasPages())
                        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                            {{ $changeRequests->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
