@extends('admin.layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                        Profile Change Requests
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Review and approve student profile change requests
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('admin.profile-change-requests.history') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors dark:focus:ring-offset-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        View History
                    </a>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="mb-6">
                <div class="sm:hidden">
                    <select onchange="window.location.href = this.value" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="?status=pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="?status=approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="?status=rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="?status=all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex space-x-4">
                        <a href="?status=pending" class="px-3 py-2 font-medium text-sm rounded-md {{ $status === 'pending' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Pending
                        </a>
                        <a href="?status=approved" class="px-3 py-2 font-medium text-sm rounded-md {{ $status === 'approved' ? 'bg-green-100 text-green-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Approved
                        </a>
                        <a href="?status=rejected" class="px-3 py-2 font-medium text-sm rounded-md {{ $status === 'rejected' ? 'bg-red-100 text-red-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Rejected
                        </a>
                        <a href="?status=all" class="px-3 py-2 font-medium text-sm rounded-md {{ $status === 'all' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700' }}">
                            All
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Table -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Changes
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($changeRequests->groupBy('student_id') as $studentId => $requests)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $requests->first()->student->full_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $requests->first()->student->cne }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ $requests->count() }} field(s) changed
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $status = $requests->first()->status;
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($status === 'approved') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $requests->first()->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.profile-change-requests.show', $studentId) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No profile change requests found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $changeRequests->links() }}
            </div>
        </div>
    </div>
@endsection
