<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Erms\EmployeeEventController;
use App\Http\Controllers\Erms\Form\EmployeeLearnerProgressFormController;
use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationMonitoringFormController;

use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationPlanFormController;
use App\Http\Controllers\Erms\Form\EmployeeLearningImplementationFormController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\Library\EventCategoryController;
use App\Http\Controllers\Event\Library\EventModeController;
use App\Http\Controllers\Event\Library\EventSourceController;
use App\Http\Controllers\Event\Library\EventTitleController;
use App\Http\Controllers\Event\Library\EventTypeController;
use App\Http\Controllers\Event\Library\EventVenueController;
use App\Http\Controllers\Event\ScheduleController;
use App\Http\Controllers\HR\CertificateController;
use App\Http\Controllers\HR\DashboardController;
use App\Http\Controllers\HR\EmployeeAttendanceController;
use App\Http\Controllers\HR\EmployeeFormSubmissionController;
use App\Http\Controllers\HR\NominatedEmployeeController;
use App\Http\Controllers\HR\OfficeEmployeeController;
use App\Http\Controllers\Mobile\EventAttendanceController;
use App\Http\Controllers\Mobile\QR\ScanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Office\EmployeeController;
use App\Http\Controllers\Office\EventController as OfficeEventController;

use App\Http\Controllers\Office\OfficeController;;

use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;



Route::prefix('mobile')->group(function () {
    Route::get('/event', [EventAttendanceController::class, 'getListEvent']);
    Route::get('/event-attendance/{eventScheduleId}', [EventAttendanceController::class, 'getEventScheduleEmployeeAttendance']);

    Route::prefix('scan')->group(function () {
        Route::post('/attendance', [ScanController::class, 'store']);
    });
});



        Route::prefix('certification')->group(function(){
           Route::get('/preview/{nominatedEmployeeId}', [CertificateController::class, 'preview']); // list of employee for 
         Route::post('/send/{nominatedEmployeeId}', [CertificateController::class, 'send']); 
    });



Route::middleware('auth:sanctum')->group(function () {


    Route::prefix('user')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum']);
        Route::put('/edit/{userId}', [UserController::class, 'edit']);
        Route::delete('/delete/{userId}', [UserController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

  

    Route::prefix('erms')->group(function () {
        Route::get('/index/{controlNo}', [EmployeeEventController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
        Route::get('/view/{eventId}/{controlNo}', [EmployeeEventController::class, 'show'])->withoutMiddleware(['auth:sanctum']);

        Route::prefix('learner-progress')->group(function () {
            Route::get('/{learnerProgressFormId}', [EmployeeLearnerProgressFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearnerProgressFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learnerProgressFormId}', [EmployeeLearnerProgressFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learnerProgressFormId}', [EmployeeLearnerProgressFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });

        Route::prefix('learning-application-monitoring')->group(function () {
            Route::get('/{learningMonitoringFormId}', [EmployeeLearningApplicationMonitoringFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationMonitoringFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningMonitoringFormId}', [EmployeeLearningApplicationMonitoringFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningMonitoringFormId}', [EmployeeLearningApplicationMonitoringFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });

        Route::prefix('learning-application-plan')->group(function () {
            Route::get('/{learningApplicationPlanFormId}', [EmployeeLearningApplicationPlanFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationPlanFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningApplicationPlanFormId}', [EmployeeLearningApplicationPlanFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningApplicationPlanFormId}', [EmployeeLearningApplicationPlanFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });

        Route::prefix('learner-implementation')->group(function () {
            Route::get('/{learningImplementationFormId}', [EmployeeLearningImplementationFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningImplementationFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningImplementationFormId}', [EmployeeLearningImplementationFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningImplementationFormId}', [EmployeeLearningImplementationFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
    });
});

//------------------------------------------------------office admin -------------------------------------------------------------\\


Route::middleware(['auth:sanctum', 'role:office_admin'])->group(function () {

    Route::prefix('office')->group(function () {

        Route::prefix('employee')->group(function () {
            Route::get('/', [OfficeController::class, 'show']);
            Route::post('/store', [EmployeeController::class, 'store']);
            Route::get('/nomination-list', [EmployeeController::class, 'index']);
            Route::put('/nominated-employee/{nominatedEmployeeId}/reason', [EmployeeController::class, 'editReason']);
            Route::delete('/delete/{nominatedEmployeeId}', [EmployeeController::class, 'destory']); //  employee remove on the nominated event
        });

        Route::prefix('event')->group(function () {
            Route::get('/list-of-event', [OfficeEventController::class, 'index']);
            Route::get('/view-event/{scheduleId}', [OfficeEventController::class, 'show']);
            Route::get('/employee/nominate/{scheduleId}', [EmployeeController::class, 'employeeNominatedByOffice']); // list of employee nominated

        });
    });
});


//------------------------------------------------------hr admin -------------------------------------------------------------\\

Route::middleware(['auth:sanctum', 'role:hr_admin'])->group(function () {

    Route::prefix('hr')->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/up-coming/events', [DashboardController::class, 'index']);
            Route::get('/calendar', [DashboardController::class, 'calendar']);
        });


        Route::prefix('employee')->group(function () {
            Route::get('/list/suggested/training/{office}', [OfficeEmployeeController::class, 'show']);
        });
    });


    // Roles
    Route::prefix('role')->group(function () {
        Route::get('/index', [RoleController::class, 'index']);
        Route::post('/store', [RoleController::class, 'store']);
        Route::put('/update/{roleId}', [RoleController::class, 'update']);
        Route::delete('/destroy/{roleId}', [RoleController::class, 'destroy']);
    });

    // Permission
    Route::prefix('permission')->group(function () {
        Route::get('/index', [PermissionController::class, 'index']);
        Route::post('/store', [PermissionController::class, 'store']);
        Route::put('/update/{permissionId}', [PermissionController::class, 'update']);
        Route::delete('/destroy/{permissionId}', [PermissionController::class, 'destroy']);
    });

    Route::prefix('type')->group(function () {
        Route::get('/', [EventTypeController::class, 'index']);
        Route::post('/store', [EventTypeController::class, 'store']);
        Route::put('/update/{typeId}', [EventTypeController::class, 'update']);
        Route::delete('/delete/{typeId}', [EventTypeController::class, 'destroy']);
    });

    Route::prefix('mode')->group(function () {
        Route::get('/', [EventModeController::class, 'index']);
        Route::post('/store', [EventModeController::class, 'store']);
        Route::put('/update/{modeId}', [EventModeController::class, 'update']);
        Route::delete('/delete/{modeId}', [EventModeController::class, 'destroy']);
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [EventCategoryController::class, 'index']);
        Route::post('/store', [EventCategoryController::class, 'store']);
        Route::put('/update/{categoryId}', [EventCategoryController::class, 'update']);
        Route::delete('/delete/{categoryId}', [EventCategoryController::class, 'destroy']);
    });

    Route::prefix('source')->group(function () {
        Route::get('/index', [EventSourceController::class, 'index']);
        Route::post('/store', [EventSourceController::class, 'store']);
        Route::put('/update/{sourceId}', [EventSourceController::class, 'update']);
        Route::delete('/delete/{sourceId}', [EventSourceController::class, 'destroy']);
    });

    Route::prefix('title')->group(function () {
        Route::get('/', [EventTitleController::class, 'index']);
        Route::post('/store', [EventTitleController::class, 'store']);
        Route::put('/update/{titleId}', [EventTitleController::class, 'update']);
        Route::delete('/delete/{titleId}', [EventTitleController::class, 'destroy']);
    });


    Route::prefix('venue')->group(function () {
        Route::get('/', [EventVenueController::class, 'index']);
        Route::post('/store', [EventVenueController::class, 'store']);
        Route::put('/update/{venueId}', [EventVenueController::class, 'update']);
        Route::delete('/delete/{venueId}', [EventVenueController::class, 'destroy']);
    });

    Route::prefix('event')->group(function () {
        Route::get('/index', [EventController::class, 'index']);
        Route::post('/store', [EventController::class, 'store']);
        Route::put('/edit/{eventId}', [EventController::class, 'edit']);
        Route::get('/view/{eventId}', [EventController::class, 'view']);
        Route::get('/details/{eventId}/{eventScheduleId}', [EventController::class, 'eventDetails']);
        Route::get('/nominated-employee/{eventId}/{eventScheduleId}', [EventController::class, 'show']);
        Route::delete('/delete/{eventId}', [EventController::class, 'destory']);

        Route::prefix('schedule')->group(function () {
            Route::post('/store', [ScheduleController::class, 'store']);
            Route::put('/edit/{eventScheduleId}', [ScheduleController::class, 'edit']);
            Route::get('/view/{eventScheduleId}', [ScheduleController::class, 'view']);
            Route::put('/update-status/{eventScheduleId}', [ScheduleController::class, 'update']);
            Route::delete('/delete/{eventScheduleId}', [ScheduleController::class, 'destory']);
        });


        Route::prefix('employee')->group(function () {
            Route::put('/nomination/approval/{nominatedEmployeeId}', [NominatedEmployeeController::class, 'update']);
            Route::get('/information/{nominatedEmployeeId}', [NominatedEmployeeController::class, 'show']);
            Route::get('/list/forms/', [EmployeeFormSubmissionController::class, 'index']);
            Route::get('/forms', [EmployeeFormSubmissionController::class, 'show']);

            Route::prefix('submission')->group(function () {
            Route::put('/update/{employeeFormSubmissionId}', [EmployeeFormSubmissionController::class, 'update']); // approved or returned with remarks
            Route::get('/list', [EmployeeFormSubmissionController::class, 'listOfEmployeeSubmitted']); // list of employee submitted

            });

            Route::prefix('attendance')->group(function () {
                Route::get('/employee/{nominatedEmployeeId}', [EmployeeAttendanceController::class, 'show']);
            });
        });
    });




    Route::prefix('user')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::put('/update/{userId}', [AuthController::class, 'update']);
        Route::delete('/delete/{userId}', [UserController::class, 'destroy']);
    });

    Route::prefix('office')->group(function () {
        Route::get('/index', [OfficeController::class, 'index']);
        Route::get('/employee/{office}', [OfficeController::class, 'employee']);
    });
});
