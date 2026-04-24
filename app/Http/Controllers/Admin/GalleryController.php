<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Gallery List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Gallery::with('images')->latest();

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $galleries = $query->paginate(10);

        return view('admin.galleries.index', compact('galleries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Page
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Gallery
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'event_date' => 'nullable|date',
            'category' => 'nullable',
            'status' => 'required',
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $gallery = Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'category' => $request->category,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = time() . '_' . rand(100,999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/galleries'), $fileName);

                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image' => $fileName,
                ]);
            }
        }

        return redirect('/galleries')->with('success', 'Gallery created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Page
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);

        return view('admin.galleries.edit', compact('gallery'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Gallery
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'event_date' => 'nullable|date',
            'category' => 'nullable',
            'status' => 'required',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = time() . '_' . rand(100,999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/galleries'), $fileName);

                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image' => $fileName,
                ]);
            }
        }

        return redirect('/galleries')->with('success', 'Gallery updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Single
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        Gallery::findOrFail($id)->delete();

        return back()->with('success', 'Gallery deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */
    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            Gallery::whereIn('id', $request->ids)->delete();
        }

        return back()->with('success', 'Selected galleries deleted successfully');
    }
	
	public function deleteImage($id)
	{
		$image = \App\Models\GalleryImage::findOrFail($id);

		$image->delete();

		return back()->with(
			'success',
			'Gallery image deleted successfully'
		);
	}
}