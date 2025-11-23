<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // list all students
    public function index()
    {
        $students = Student::orderBy('name')->get();
        return view('student.index', compact('students'));
    }

    // create form
    public function create()
    {
        return view('student.create');
    }

    // store student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|size:8|unique:students,phone',
            'national_id'       => 'nullable|string|max:11|unique:students,national_id',
            'nationality'       => 'required|string|max:255',
            'dob'               => 'required|date',
            'age'               => 'nullable|string|max:3',
            'state'             => 'required|string|max:255',
            'wilaya'            => 'required|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'branch'            => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
        ]);

        Student::create($validated);

        return redirect()->route('student.index')->with('success', 'تم إضافة الطالب بنجاح');
    }

    // show student
    public function show(Student $student)
    {
        return view('student.show', compact('student'));
    }

    // edit student
    public function edit(Student $student)
    {
        return view('student.edit', compact('student'));
    }

    // update student
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|size:8|unique:students,phone,' . $student->id,
            'national_id'       => 'nullable|string|max:11|unique:students,national_id,' . $student->id,
            'nationality'       => 'required|string|max:255',
            'dob'               => 'required|date',
            'age'               => 'nullable|string|max:3',
            'state'             => 'required|string|max:255',
            'wilaya'            => 'required|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'branch'            => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
        ]);

        $student->update($validated);

        return redirect()->route('student.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    // delete student
    public function destroy(Student $student)
    {
        // $student->delete();
        // return back()->with('success', 'تم حذف الطالب بنجاح');
    }
}
