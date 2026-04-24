<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSubjectController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\FeeTypeController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\FeePaymentController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HomeworkController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/login'));


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    //Route::view('/dashboard', 'dashboard')->name('dashboard');
	Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // AJAX
    Route::get('/get-sections/{class_id}', function ($class_id) {
        return \App\Models\Section::where('class_id', $class_id)->get();
    });
	
	Route::get('/get-subjects/{class_id}', function ($class_id) {
		return \App\Models\ClassSubject::with('subject')
			->where('class_id', $class_id)
			->get();
	});
	
	Route::get('/get-class-section-subjects/{class_id}/{section_id}', function ($class_id, $section_id) {

			/*
			|--------------------------------------------------------------------------
			| Get Subjects by Class + Section
			|--------------------------------------------------------------------------
			| Uses teacher_assignments table
			| Only assigned subjects should be shown
			|--------------------------------------------------------------------------
			*/

			return \App\Models\TeacherAssignment::with('subject')
				->where('class_id', $class_id)
				->where('section_id', $section_id)
				->get()
				->map(function ($row) {
					return [
						'id' => $row->subject->id ?? null,
						'name' => $row->subject->name ?? '',
					];
				})
				->unique('id')
				->values();

		});
	
	Route::get('/get-students/{class_id}/{section_id}', function ($class_id, $section_id) {

		return \App\Models\Student::with('user')
			->where('class_id', $class_id)
			->where('section_id', $section_id)
			->get()
			->map(function ($student) {
				return [
					'id' => $student->id,
					'name' => $student->user->name
				];
			});

	});
	
	Route::get('/get-teachers/{class_id}', function ($class_id) {

		/*
		|--------------------------------------------------------------------------
		| Get teachers assigned to selected class
		|--------------------------------------------------------------------------
		| Uses teacher_assignments table
		|--------------------------------------------------------------------------
		*/

		return \App\Models\TeacherAssignment::with([
			'teacher.user'
		])
		->where('class_id', $class_id)
		->get()
		->map(function ($row) {
			return [
				'id' => $row->teacher->id ?? null,
				'name' => $row->teacher->user->name ?? '',
			];
		})
		->unique('id')
		->values();

	});
	
	Route::get('/get-homework-teachers/{class_id}/{section_id}/{subject_id}', function (
    $class_id,
    $section_id,
    $subject_id
) {
    return \App\Models\TeacherAssignment::with('teacher.user')
        ->where('class_id', $class_id)
        ->where('section_id', $section_id)
        ->where('subject_id', $subject_id)
        ->get()
        ->map(function ($row) {
            return [
                'id' => $row->teacher->id ?? null,
                'name' => $row->teacher->user->name ?? '',
            ];
        })
        ->unique('id')
        ->values();
});
	
	Route::get('/get-attendance/{class}/{section}/{date}', [AttendanceController::class, 'getStudents']);
	Route::get('/get-student-fees/{student_id}', [FeePaymentController::class,'getStudentFees']);


    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */
    Route::prefix('classes')->controller(ClassController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });    
	
	
	/*
    |--------------------------------------------------------------------------
    | Class Subjects
    |--------------------------------------------------------------------------
    */
    Route::prefix('class-subjects')->controller(ClassSubjectController::class)->group(function () {
        Route::get('/',  'index');
		Route::post('/store',  'store');
		Route::get('/create', 'create');
    });


    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    */
    Route::prefix('sections')->controller(SectionController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->controller(StudentController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });
	



    /*
    |--------------------------------------------------------------------------
    | Teachers
    |--------------------------------------------------------------------------
    */
    Route::prefix('teachers')->controller(TeacherController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });


    /*
    |--------------------------------------------------------------------------
    | Teacher Assignments
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher-assignments')->controller(TeacherAssignmentController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');

        Route::get('/edit/{teacher}/{class}/{section}', 'edit');
        Route::post('/update', 'update');

        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */
    Route::prefix('subjects')->controller(SubjectController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::post('/bulk-delete', 'bulkDelete');
    });
	/*
    |--------------------------------------------------------------------------
    | Timetable
    |--------------------------------------------------------------------------
    */
	
	Route::prefix('timetable')->controller(TimetableController::class)->group(function () {
		Route::get('/', 'index');
		Route::get('/create', 'create');
		Route::post('/store', 'store');

		Route::get('/edit/{id}', 'edit');
		Route::post('/update/{id}', 'update');

		Route::delete('/delete/{id}', 'destroy');
		Route::get('/grid','grid');
	});	
	
	/*
    |--------------------------------------------------------------------------
    | TimeSlots
    |--------------------------------------------------------------------------
    */
	
	Route::prefix('time-slots')->controller(TimeSlotController::class)->group(function () {
		Route::get('/', 'index');
		Route::get('/create', 'create');
		Route::post('/store', 'store');
		Route::get('/edit/{id}', 'edit');
		Route::post('/update/{id}', 'update');
		Route::delete('/delete/{id}', 'destroy');
		Route::post('/bulk-delete', 'bulkDelete');
	});


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });    
	
	/*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::prefix('attendance')->controller(AttendanceController::class)->group(function () {
		Route::get('/', 'index');
		Route::get('/create', 'create');
		Route::post('/store', 'store');
		Route::get('/report', 'report');
	});
	
	/*
    |--------------------------------------------------------------------------
    | Fee Types
    |--------------------------------------------------------------------------
    */
    Route::prefix('fee-types')->controller(FeeTypeController::class)->group(function () {

			Route::get('/', 'index');
			Route::get('/create', 'create');
			Route::post('/store', 'store');

			Route::get('/edit/{id}', 'edit');
			Route::post('/update/{id}', 'update');

			Route::delete('/delete/{id}', 'destroy');
			Route::post('/bulk-delete', 'bulkDelete');

	});	
	
	/*
    |--------------------------------------------------------------------------
    | Fee Types
    |--------------------------------------------------------------------------
    */
    

	Route::prefix('student-fees')->controller(StudentFeeController::class)->group(function () {

			Route::get('/', 'index');
			Route::get('/create', 'create');
			Route::post('/store', 'store');

			Route::get('/edit/{id}', 'edit');
			Route::post('/update/{id}', 'update');

			Route::delete('/delete/{id}', 'destroy');
			Route::post('/bulk-delete', 'bulkDelete');

	});	
	/*
    |--------------------------------------------------------------------------
    | Fee Payments
    |--------------------------------------------------------------------------
    */
	Route::prefix('fee-payments')->controller(FeePaymentController::class)->group(function () {

			Route::get('/', 'index');
			Route::get('/create', 'create');
			Route::post('/store', 'store');

			Route::get('/edit/{id}', 'edit');
			Route::post('/update/{id}', 'update');

			Route::delete('/delete/{id}', 'destroy');
			Route::post('/bulk-delete', 'bulkDelete');

		});
		
	/*
    |--------------------------------------------------------------------------
    | Notices
    |--------------------------------------------------------------------------
    */
	Route::prefix('notices')->group(function () {

		Route::get('/', [NoticeController::class, 'index']);
		Route::get('/create', [NoticeController::class, 'create']);
		Route::post('/store', [NoticeController::class, 'store']);

		Route::get('/edit/{id}', [NoticeController::class, 'edit']);
		Route::post('/update/{id}', [NoticeController::class, 'update']);

		Route::delete('/delete/{id}', [NoticeController::class, 'destroy']);
		Route::post('/bulk-delete', [NoticeController::class, 'bulkDelete']);

	});
	
	/*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */
	
	Route::prefix('galleries')->group(function () {

		Route::get('/', [GalleryController::class, 'index']);
		Route::get('/create', [GalleryController::class, 'create']);
		Route::post('/store', [GalleryController::class, 'store']);

		Route::get('/edit/{id}', [GalleryController::class, 'edit']);
		Route::post('/update/{id}', [GalleryController::class, 'update']);

		Route::delete('/delete/{id}', [GalleryController::class, 'destroy']);
		Route::post('/bulk-delete', [GalleryController::class, 'bulkDelete']);
		Route::delete('/delete-image/{id}',[GalleryController::class, 'deleteImage']);

	});	
	
	/*
    |--------------------------------------------------------------------------
    | Homework
    |--------------------------------------------------------------------------
    */
	
	Route::prefix('homeworks')->group(function () {

		Route::get('/', [HomeworkController::class, 'index']);
		Route::get('/create', [HomeworkController::class, 'create']);
		Route::post('/store', [HomeworkController::class, 'store']);

		Route::get('/edit/{id}', [HomeworkController::class, 'edit']);
		Route::post('/update/{id}', [HomeworkController::class, 'update']);

		Route::delete('/delete/{id}', [HomeworkController::class, 'destroy']);
		Route::post('/bulk-delete', [HomeworkController::class, 'bulkDelete']);

	});
	
	

});


require __DIR__.'/auth.php';