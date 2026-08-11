<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Erms\EmployeeEventController;
use App\Http\Controllers\Erms\Form\EmployeeLearnerProgressReportController;
use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationMonitoringReportController;
use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationPlanController;
use App\Http\Controllers\Erms\Form\EmployeeLearningImplementationReportController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\Library\EventModeController;
use App\Http\Controllers\Event\Library\EventSourceController;
use App\Http\Controllers\Event\Library\EventTitleController;
use App\Http\Controllers\Event\Library\EventTypeController;
use App\Http\Controllers\Event\Library\EventVenueController;
use App\Http\Controllers\Office\EmployeeController;

use App\Http\Controllers\Office\OfficeController;;

use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('user')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::prefix('type')->group(function () {
        Route::get('/index', [EventTypeController::class, 'index']);
        Route::post('/store', [EventTypeController::class, 'store']);
        Route::put('/update/{typeId}', [EventTypeController::class, 'update']);
        Route::delete('/delete/{typeId}', [EventTypeController::class, 'destroy']);
    });

    Route::prefix('mode')->group(function () {
        Route::get('/index', [EventModeController::class, 'index']);
        Route::post('/store', [EventModeController::class, 'store']);
        Route::put('/update/{modeId}', [EventModeController::class, 'update']);
        Route::delete('/delete/{modeId}', [EventModeController::class, 'destroy']);
    });

    Route::prefix('source')->group(function () {
        Route::get('/index', [EventSourceController::class, 'index']);
        Route::post('/store', [EventSourceController::class, 'store']);
        Route::put('/update/{sourceId}', [EventSourceController::class, 'update']);
        Route::delete('/delete/{sourceId}', [EventSourceController::class, 'destroy']);
    });

    Route::prefix('title')->group(function () {
        Route::get('/index', [EventTitleController::class, 'index']);
        Route::post('/store', [EventTitleController::class, 'store']);
        Route::put('/update/{titleId}', [EventTitleController::class, 'update']);
        Route::delete('/delete/{titleId}', [EventTitleController::class, 'destroy']);
    });


    Route::prefix('venue')->group(function () {
        Route::get('/index', [EventVenueController::class, 'index']);
        Route::post('/store', [EventVenueController::class, 'store']);
        Route::put('/update/{venueId}', [EventVenueController::class, 'update']);
        Route::delete('/delete/{venueId}', [EventVenueController::class, 'destory']);
    });

    Route::prefix('event')->group(function () {
        Route::get('/index', [EventController::class, 'index']);
        Route::post('/store', [EventController::class, 'store']);
        // Route::put('/update/{event}', [EventController::class, 'update']);
        Route::get('view/{eventId}', [EventController::class, 'view']);
        Route::delete('/delete/{eventId}', [EventController::class, 'destory']);
    });

    Route::prefix('employee')->group(function () {
        Route::post('/store', [EmployeeController::class, 'store']);
        Route::delete('/delete{nominatedId}', [EmployeeController::class, 'destory']);
    });

    Route::prefix('office')->group(function () {
        Route::get('/index', [OfficeController::class, 'index']);
        Route::get('/employee', [OfficeController::class, 'show']);
    });


    // Roles
    Route::prefix('role')->group(function () {
        Route::get('/index', [RoleController::class, 'index']);
        Route::post('/store', [RoleController::class, 'store']);
    });

    // Permission
    Route::prefix('permission')->group(function () {
        Route::get('/index', [PermissionController::class, 'index']);
        Route::post('/store', [PermissionController::class, 'store']);
    });


    Route::prefix('erms')->group(function () {
        Route::get('/index/{controlNo}', [EmployeeEventController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
        Route::get('/view/{eventId}/{controlNo}', [EmployeeEventController::class, 'show'])->withoutMiddleware(['auth:sanctum']);


        Route::prefix('learner-progress')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearnerProgressReportController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearnerProgressReportController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learnerProgressReportId}', [EmployeeLearnerProgressReportController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learnerProgressReportId}', [EmployeeLearnerProgressReportController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });

        Route::prefix('learning-application-monitoring')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningApplicationMonitoringReportController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationMonitoringReportController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningApplicationMonitoringId}', [EmployeeLearningApplicationMonitoringReportController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningApplicationMonitoringId}', [EmployeeLearningApplicationMonitoringReportController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });

            Route::prefix('learning-application-plan')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningApplicationPlanController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationPlanController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningApplicationPlanId}', [EmployeeLearningApplicationPlanController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningApplicationPlanId}', [EmployeeLearningApplicationPlanController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });

              Route::prefix('learner-implementation')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningImplementationReportController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningImplementationReportController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningImplementationId}', [EmployeeLearningImplementationReportController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningImplementationId}', [EmployeeLearningImplementationReportController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });
    });
});
