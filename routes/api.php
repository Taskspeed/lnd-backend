<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Erms\EmployeeEventController;
use App\Http\Controllers\Erms\Form\EmployeeLearnerProgressFormController;
use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationMonitoringFormController;

use App\Http\Controllers\Erms\Form\EmployeeLearningApplicationPlanFormController;
use App\Http\Controllers\Erms\Form\EmployeeLearningImplementationFormController;
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
        Route::put('/edit/{event}', [EventController::class, 'edit']);
        Route::put('/status/{eventId}', [EventController::class, 'update']);
        Route::get('/view/{eventId}', [EventController::class, 'view']);
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
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearnerProgressFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearnerProgressFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learnerProgressFormId}', [EmployeeLearnerProgressFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learnerProgressFormId}', [EmployeeLearnerProgressFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });

        Route::prefix('learning-application-monitoring')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningApplicationMonitoringFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationMonitoringFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningMonitoringFormId}', [EmployeeLearningApplicationMonitoringFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningMonitoringFormId}', [EmployeeLearningApplicationMonitoringFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);
        });

        Route::prefix('learning-application-plan')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningApplicationPlanFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningApplicationPlanFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningApplicationPlanFormId}', [EmployeeLearningApplicationPlanFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningApplicationPlanFormId}', [EmployeeLearningApplicationPlanFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });

        Route::prefix('learner-implementation')->group(function () {
            Route::get('/{eventId}/{formName}/{controlNo}', [EmployeeLearningImplementationFormController::class, 'show'])->withoutMiddleware(['auth:sanctum']);
            Route::post('/store', [EmployeeLearningImplementationFormController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
            Route::put('/update/{learningImplementationFormId}', [EmployeeLearningImplementationFormController::class, 'update'])->withoutMiddleware(['auth:sanctum']);
            Route::delete('/delete/{learningImplementationFormId}', [EmployeeLearningImplementationFormController::class, 'destroy'])->withoutMiddleware(['auth:sanctum']);

            });
    });
});
