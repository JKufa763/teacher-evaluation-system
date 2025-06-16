<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class_;
use App\Models\Grade;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Class_::with('grade')->orderBy('name')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $grades = Grade::all();
        return view('admin.classes.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'grade_id' => 'required|exists:grades,id',
        ]);

        Class_::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully!');
    }

    public function edit(Class_ $class)
    {
        $grades = Grade::all();
        return view('admin.classes.edit', compact('class', 'grades'));
    }

    public function update(Request $request, Class_ $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'grade_id' => 'required|exists:grades,id',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(Class_ $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully!');
    }
}