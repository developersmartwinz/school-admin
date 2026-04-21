<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // LIST
    public function index(Request $request)
	{
		$query = Teacher::with('user');

		// 🔍 Search
		if ($request->search) {
			$query->whereHas('user', function ($q) use ($request) {
				$q->where('name', 'like', "%{$request->search}%")
				  ->orWhere('email', 'like', "%{$request->search}%");
			});
		}

		$teachers = $query->latest()->paginate(10);

		return view('admin.teachers.index', compact('teachers'));
	}

    // CREATE
    public function create()
    {
        return view('admin.teachers.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher'
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'designation' => $request->designation,
        ]);

        return redirect('/teachers')->with('success', 'Teacher created');
    }

    // EDIT
    public function edit($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $teacher->user_id,
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $teacher->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $teacher->update([
            'subject' => $request->subject,
            'designation' => $request->designation,
        ]);

        return redirect('/teachers')->with('success', 'Teacher updated');
    }

    // DELETE
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->user()->delete();

        return back()->with('success', 'Deleted');
    }
	public function bulkDelete(Request $request)
	{
		$teachers = Teacher::whereIn('id', $request->ids)->get();

		foreach ($teachers as $teacher) {
			$teacher->user()->delete();
		}

		return back()->with('success', 'Selected teachers deleted');
	}
}