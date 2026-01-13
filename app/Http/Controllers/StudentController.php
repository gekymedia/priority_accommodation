<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['currentBooking.room']);

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('student_id', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $query->whereHas('currentBooking');
            } elseif ($request->status == 'inactive') {
                $query->whereDoesntHave('currentBooking');
            }
        }

        if ($request->has('university') && $request->university) {
            $query->where('university', 'like', "%{$request->university}%");
        }

        if ($request->has('department') && $request->department) {
            $query->where('department', 'like', "%{$request->department}%");
        }

        $students = $query->latest()->paginate(15);

        $totalStudents = Student::count();
        $activeStudents = Student::has('currentBooking')->count();
        $inactiveStudents = Student::doesntHave('currentBooking')->count();

        $universities = Student::select('university')
            ->whereNotNull('university')
            ->distinct()
            ->pluck('university');

        $departments = Student::select('department')
            ->whereNotNull('department')
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
            return redirect()->route('students.show', $student)
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

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->currentBooking) {
            return redirect()->back()
                ->with('error', 'Cannot delete student with active booking. Please check out the student first.');
        }

        $student->delete();

        return redirect()->route('students.index')
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