<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Notice List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Notice::latest();

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sent_by', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $notices = $query->paginate(10);

        return view('admin.notices.index', compact('notices'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Page
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.notices.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Notice
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'notice_date' => 'required|date',
            'notice_time' => 'nullable',
            'sent_by' => 'nullable',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'status' => 'required',
        ]);

        $attachment = null;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '_' . $request->file('attachment')->getClientOriginalName();
            $request->file('attachment')->move(public_path('uploads/notices'), $attachment);
        }

        Notice::create([
            'title' => $request->title,
            'description' => $request->description,
            'notice_date' => $request->notice_date,
            'notice_time' => $request->notice_time,
            'sent_by' => $request->sent_by,
            'attachment' => $attachment,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect('/notices')->with('success', 'Notice created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Page
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $notice = Notice::findOrFail($id);

        return view('admin.notices.edit', compact('notice'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Notice
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'notice_date' => 'required|date',
            'notice_time' => 'nullable',
            'sent_by' => 'nullable',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'status' => 'required',
        ]);

        $attachment = $notice->attachment;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '_' . $request->file('attachment')->getClientOriginalName();
            $request->file('attachment')->move(public_path('uploads/notices'), $attachment);
        }

        $notice->update([
            'title' => $request->title,
            'description' => $request->description,
            'notice_date' => $request->notice_date,
            'notice_time' => $request->notice_time,
            'sent_by' => $request->sent_by,
            'attachment' => $attachment,
            'status' => $request->status,
        ]);

        return redirect('/notices')->with('success', 'Notice updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Single
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        Notice::findOrFail($id)->delete();

        return back()->with('success', 'Notice deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */
    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            Notice::whereIn('id', $request->ids)->delete();
        }

        return back()->with('success', 'Selected notices deleted successfully');
    }
}