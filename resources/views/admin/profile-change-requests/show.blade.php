@extends('admin.layouts.app')

@section('content')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Profile Changes for {{ $student->full_name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        CNE: {{ $student->cne }} | Apogee: {{ $student->apogee }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('admin.profile-change-requests.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="mb-6 flex gap-3">
                <form action="{{ route('admin.profile-change-requests.approve-all', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve ALL changes for this student?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Approve All
                    </button>
                </form>

                <button onclick="document.getElementById('rejectAllModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Reject All
                </button>
            </div>

            <!-- Changes List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($pendingChanges as $change)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ $change->field_label }}
                                        </p>
                                        <div class="mt-2 flex justify-between">
                                            <div class="sm:flex sm:justify-between w-full">
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-500">Old Value:</p>
                                                    <p class="mt-1 text-sm text-gray-900 line-through">
                                                        {{ $change->old_value ?: '—' }}
                                                    </p>
                                                </div>
                                                <div class="flex-1 ml-6">
                                                    <p class="text-xs text-gray-500">New Value:</p>
                                                    <p class="mt-1 text-sm font-semibold text-green-600">
                                                        {{ $change->new_value ?: '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            Requested {{ $change->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="ml-5 flex-shrink-0 flex gap-2">
                                        <form action="{{ route('admin.profile-change-requests.approve', $change->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $change->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('rejectModal').classList.add('hidden')">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Change Request</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject
                        </button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject All Modal -->
    <div id="rejectAllModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('rejectAllModal').classList.add('hidden')">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.profile-change-requests.reject-all', $student->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject All Change Requests</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject All
                        </button>
                        <button type="button" onclick="document.getElementById('rejectAllModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(id) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/profile-change-requests/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }
    </script>
@endsection
