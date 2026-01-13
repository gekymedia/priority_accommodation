<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Room {{ $room->room_number }} - Occupants
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $room->hostel->name }} • Capacity: {{ $room->capacity }} person(s)
                </p>
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
                    {{ $room->beds_available > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <i class="fas fa-bed mr-2"></i>
                    {{ $room->beds_available }}/{{ $room->capacity }} Beds Available
                </span>
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-secondary px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Room
                </a>
            </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Current Occupants -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                Current Occupants ({{ $room->activeOccupants->count() }})
                            </h3>
                        </div>

                        @if($room->activeOccupants->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($room->activeOccupants as $occupant)
                                    <div class="p-6 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-4">
                                                @if($occupant->profile_picture_url)
                                                    <img src="{{ $occupant->profile_picture_url }}" 
                                                         alt="{{ $occupant->display_name }}" 
                                                         class="w-14 h-14 rounded-full object-cover border-2 border-gray-200">
                                                @else
                                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-bold text-xl">
                                                        {{ substr($occupant->display_name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 text-lg flex items-center">
                                                        {{ $occupant->display_name }}
                                                        @if($occupant->is_system_booking)
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                <i class="fas fa-globe mr-1"></i> Online Booking
                                                            </span>
                                                        @else
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                <i class="fas fa-user-plus mr-1"></i> Walk-in
                                                            </span>
                                                        @endif
                                                    </h4>
                                                    @if($occupant->contact_phone)
                                                        <p class="text-sm text-gray-500">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            {{ $occupant->contact_phone }}
                                                        </p>
                                                    @endif
                                                    @if($occupant->contact_email)
                                                        <p class="text-sm text-gray-500">
                                                            <i class="fas fa-envelope mr-1"></i>
                                                            {{ $occupant->contact_email }}
                                                        </p>
                                                    @endif
                                                    @if($occupant->student_id_number)
                                                        <p class="text-sm text-gray-500">
                                                            <i class="fas fa-id-card mr-1"></i>
                                                            {{ $occupant->student_id_number }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-bed mr-1"></i>
                                                    Bed {{ $occupant->bed_number ?? 'N/A' }}
                                                </div>
                                                @if($occupant->move_in_date)
                                                    <p class="text-sm text-gray-500 mt-2">
                                                        Moved in: {{ $occupant->move_in_date->format('M d, Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($occupant->notes)
                                            <div class="mt-3 p-3 bg-yellow-50 rounded-lg text-sm text-yellow-800">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                {{ $occupant->notes }}
                                            </div>
                                        @endif

                                        <div class="mt-4 flex justify-end">
                                            <form action="{{ route('hostel.occupants.remove', $occupant) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to check out this occupant?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                                    Check Out
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-slash text-2xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-1">No Occupants</h4>
                                <p class="text-gray-500">This room is currently empty. Add occupants using the form.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add Occupant Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Add Walk-in Occupant
                            </h3>
                        </div>
                        <form action="{{ route('hostel.room-occupants.add', $room) }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                       placeholder="Enter occupant's name">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input type="tel" name="phone"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., 0241234567">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>
                                <input type="email" name="email"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                       placeholder="email@example.com">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Student ID Number
                                </label>
                                <input type="text" name="student_id_number"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., CUG/2024/0001">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Bed Number
                                    </label>
                                    <select name="bed_number"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                        <option value="">Auto-assign</option>
                                        @for($i = 1; $i <= $room->capacity; $i++)
                                            <option value="{{ $i }}">Bed {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Move-in Date
                                    </label>
                                    <input type="date" name="move_in_date" value="{{ date('Y-m-d') }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Academic Year
                                    </label>
                                    <input type="text" name="academic_year" value="{{ date('Y') }}/{{ date('Y') + 1 }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Semester
                                    </label>
                                    <select name="semester"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                        <option value="First Semester">First Semester</option>
                                        <option value="Second Semester">Second Semester</option>
                                        <option value="Full Year">Full Year</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes
                                </label>
                                <textarea name="notes" rows="2"
                                          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                          placeholder="Any additional notes..."></textarea>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="visible_to_roommates" value="1" checked
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700">
                                    Visible to other roommates
                                </label>
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold flex items-center justify-center transition"
                                    {{ $room->beds_available <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus-circle mr-2"></i>
                                Add Occupant
                            </button>

                            @if($room->beds_available <= 0)
                                <p class="text-center text-sm text-red-500">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Room is at full capacity
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

