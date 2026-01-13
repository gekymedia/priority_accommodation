<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Booking Requests') }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                <i class="fas fa-clock mr-1"></i>
                {{ $pendingRequests->count() }} Pending
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pending Requests -->
            @if($pendingRequests->count() > 0)
                <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-8">
                    <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-500">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Pending Confirmation (Action Required)
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($pendingRequests as $request)
                            <div class="p-6 hover:bg-gray-50 transition" id="request-{{ $request->id }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        @if($request->student->user?->profile_picture)
                                            <img src="{{ asset('storage/' . $request->student->user->profile_picture) }}" 
                                                 alt="Student" 
                                                 class="w-14 h-14 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                                {{ substr($request->student->user?->name ?? 'S', 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-semibold text-gray-900 text-lg">
                                                {{ $request->student->user?->name ?? 'Student' }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $request->student->user?->phone ?? 'N/A' }}
                                            </p>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-building mr-1"></i>
                                                    {{ $request->hostel->name }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-door-open mr-1"></i>
                                                    Room {{ $request->room->room_number }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-bed mr-1"></i>
                                                    {{ $request->beds_requested }} bed(s)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-green-600">
                                            ₵{{ number_format($request->total_amount, 2) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Commission: ₵{{ number_format($request->commission_amount, 2) }}
                                        </div>
                                        
                                        <!-- Countdown Timer -->
                                        <div class="mt-2 inline-flex items-center px-3 py-1.5 rounded-lg bg-red-100 text-red-800 font-mono text-lg font-bold"
                                             id="countdown-{{ $request->id }}"
                                             data-deadline="{{ $request->confirmation_deadline->toIso8601String() }}">
                                            <i class="fas fa-stopwatch mr-2"></i>
                                            <span class="timer">--:--</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Check-in:</span>
                                            <span class="font-medium text-gray-900 ml-1">
                                                {{ $request->check_in_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Check-out:</span>
                                            <span class="font-medium text-gray-900 ml-1">
                                                {{ $request->check_out_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Academic Year:</span>
                                            <span class="font-medium text-gray-900 ml-1">
                                                {{ $request->academic_year }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Semester:</span>
                                            <span class="font-medium text-gray-900 ml-1">
                                                {{ $request->semester }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                                    <form action="{{ route('hostel.booking-requests.confirm', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold flex items-center justify-center transition">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Confirm & Reserve Room
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            onclick="showRejectModal({{ $request->id }})"
                                            class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold flex items-center justify-center transition">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Reject (Room Unavailable)
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white shadow-lg rounded-xl p-12 text-center mb-8">
                    <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Pending Requests</h3>
                    <p class="text-gray-500">All booking requests have been processed. You'll be notified when new requests come in.</p>
                </div>
            @endif

            <!-- Recent Requests -->
            @if($recentRequests->count() > 0)
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gray-100 border-b">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            Recent Requests
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                                    {{ substr($request->student->user?->name ?? 'S', 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="font-medium text-gray-900">{{ $request->student->user?->name ?? 'Student' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $request->student->user?->phone ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->hostel->name }}</div>
                                            <div class="text-sm text-gray-500">Room {{ $request->room->room_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₵{{ number_format($request->total_amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->status === 'confirmed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Confirmed
                                                </span>
                                            @elseif($request->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i> Rejected
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-clock mr-1"></i> {{ ucfirst($request->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->updated_at->format('M d, Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Reject Booking Request</h3>
                <button onclick="hideRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection
                    </label>
                    <textarea name="rejection_reason" rows="3" required
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                              placeholder="e.g., Room has been booked by a walk-in student..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Countdown timers
        document.querySelectorAll('[id^="countdown-"]').forEach(el => {
            const deadline = new Date(el.dataset.deadline).getTime();
            
            function updateTimer() {
                const now = Date.now();
                const remaining = Math.max(0, Math.floor((deadline - now) / 1000));
                
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                
                el.querySelector('.timer').textContent = 
                    `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                
                if (remaining <= 30) {
                    el.classList.add('animate-pulse');
                }
                
                if (remaining <= 0) {
                    location.reload();
                }
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });

        // Reject modal
        function showRejectModal(requestId) {
            const form = document.getElementById('rejectForm');
            form.action = `/hostel-owner/booking-requests/${requestId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
    </script>
</x-app-layout>

