<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all users who are not admins
        $usersQuery = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', false);
            })
            ->with(['student.currentBooking.room', 'bookings' => function($q) {
                $q->whereIn('status', ['confirmed', 'checked_in'])->latest()->limit(1);
            }]);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $usersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('student_id_number', 'like', "%{$search}%")
                  ->orWhere('app_id', 'like', "%{$search}%");
            });
        }

        // Get users and map them to student-like structure
        $users = $usersQuery->latest()->get();
        
        // Map users to student-like objects for the view
        // First, ensure Student records exist for users who don't have them
        $students = $users->map(function($user) {
            // Get or create student record for this user
            $student = $user->student;
            
            if (!$student) {
                // Create or update student record from user data
                $student = Student::updateOrCreate(
                    ['email' => $user->email], // Match by email
                    [
                        'name' => $user->name,
                        'phone' => $user->phone ?? '',
                        'student_id' => $user->student_id_number ?? $user->app_id ?? ('STU' . str_pad($user->id, 6, '0', STR_PAD_LEFT)),
                        'university' => 'Catholic University of Ghana',
                        'course' => $user->programme ?? 'N/A',
                        'year_of_study' => $user->level ? (int) preg_replace('/[^0-9]/', '', $user->level) : 1,
                        'department' => $user->programme ?? 'N/A',
                        'status' => 'active',
                    ]
                );
                
                // Link student to user if there's a user_id field
                if (Schema::hasColumn('students', 'user_id')) {
                    $student->update(['user_id' => $user->id]);
                }
            } else {
                // Update student data from user to keep in sync
                $student->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? $student->phone,
                ]);
            }
            
            // Load current booking
            $student->load('currentBooking.room');
            
            return $student;
        });

        // Apply status filter after mapping
        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $students = $students->filter(function($student) {
                    return $student->currentBooking !== null;
                });
            } elseif ($request->status == 'inactive') {
                $students = $students->filter(function($student) {
                    return $student->currentBooking === null;
                });
            }
        }

        // Apply university filter
        if ($request->has('university') && $request->university) {
            $students = $students->filter(function($student) use ($request) {
                return stripos($student->university ?? '', $request->university) !== false;
            });
        }

        // Apply department filter
        if ($request->has('department') && $request->department) {
            $students = $students->filter(function($student) use ($request) {
                return stripos($student->department ?? '', $request->department) !== false;
            });
        }

        // Paginate manually
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $students->count();
        $students = $students->slice(($page - 1) * $perPage, $perPage)->values();

        // Create paginator manually
        $students = new \Illuminate\Pagination\LengthAwarePaginator(
            $students,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Calculate stats
        $totalStudents = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', false);
            })
            ->count();
            
        $activeStudents = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', false);
            })
            ->whereHas('bookings', function($q) {
                $q->whereIn('status', ['confirmed', 'checked_in']);
            })
            ->count();

        $inactiveStudents = $totalStudents - $activeStudents;

        // Get unique universities and departments from users
        $universities = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', false);
            })
            ->whereNotNull('programme')
            ->selectRaw("COALESCE(programme, 'Catholic University of Ghana') as university")
            ->distinct()
            ->pluck('university')
            ->map(function($item) {
                return 'Catholic University of Ghana'; // Standardize for now
            })
            ->unique()
            ->values();

        $departments = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', false);
            })
            ->whereNotNull('programme')
            ->select('programme as department')
            ->distinct()
            ->pluck('department');

        return view('students.index', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'universities',
            'departments'
        ));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'student_id' => 'nullable|string|max:50|unique:students,student_id',
            'department' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year_of_study' => 'required|integer|min:1|max:6',
            'date_of_birth' => 'nullable|date',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
        ]);

        // Generate student ID if not provided
        if (empty($validated['student_id'])) {
            $validated['student_id'] = 'STU' . str_pad(Student::count() + 1, 6, '0', STR_PAD_LEFT);
        }

        // ==== CRITICAL: Map to ALL database columns ====
        $studentData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'student_id' => $validated['student_id'],
            'department' => $validated['department'],
            'university' => $validated['university'],
            'course' => $validated['course'],
            'year_of_study' => $validated['year_of_study'],
            'year_level' => $validated['year_of_study'], // Map to old column
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact' => $validated['emergency_contact_name'], // Map to old column
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_phone' => $validated['emergency_contact_phone'], // Map to old column
            'status' => 'active',
            'id_proof' => null,
            'photo' => null
        ];

        try {
            $student = Student::create($studentData);
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create student: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(Student $student)
    {
        $student->load(['bookings.room.hostel', 'currentBooking.room.hostel']);
        
        $bookingStats = [
            'total' => $student->bookings->count(),
            'active' => $student->bookings->whereIn('status', ['confirmed', 'checked_in'])->count(),
            'completed' => $student->bookings->where('status', 'checked_out')->count(),
            'cancelled' => $student->bookings->where('status', 'cancelled')->count(),
        ];

        return view('students.show', compact('student', 'bookingStats'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'student_id' => 'nullable|string|max:50|unique:students,student_id,' . $student->id,
            'department' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year_of_study' => 'required|integer|min:1|max:6',
            'date_of_birth' => 'nullable|date',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
        ]);

        // Map to all columns for update too
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'student_id' => $validated['student_id'],
            'department' => $validated['department'],
            'university' => $validated['university'],
            'course' => $validated['course'],
            'year_of_study' => $validated['year_of_study'],
            'year_level' => $validated['year_of_study'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_phone' => $validated['emergency_contact_phone'],
        ];

        $student->update($updateData);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->currentBooking) {
            return redirect()->back()
                ->with('error', 'Cannot delete student with active booking. Please check out the student first.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function bookingHistory(Student $student)
    {
        $bookings = $student->bookings()
            ->with(['room.hostel'])
            ->latest()
            ->paginate(10);

        return view('students.history', compact('student', 'bookings'));
    }
}