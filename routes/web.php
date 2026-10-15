<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/preview-certificate', function () {
    return view('certificates.certificate', [
        'recipientName'  => 'Cliford M. Millan',
        'trainingTitle'  => 'Advanced Leadership and Management Skills Workshop',
        'signatoryName'  => 'Nick Sherlock',
        'signatoryTitle' => 'Manager',
        'dateIssued'     => now()->format('F d, Y'),
    ]);
});

Route::get('/header', function () {
    return view('header');
});



Route::get('/external-nomination', function () {
    return view('forms.external-nomination', [
        'officeName'     => 'City Human Resource Management Office',
        'date'           => 'September 24, 2026',
        'office'         => 'CHRMO',
        'training_title' => 'Sample Training',
        'training_date'  => 'October 5-7, 2026',
        'office_head'    => 'Juan Dela Cruz',
        'participants'   => [
            [
                'surname' => 'Santos',
                'first_name' => 'Maria',
                'mi' => 'L',
                'sex' => 'F',
                'position' => 'Admin Aide',
                'relevance' => '...',
                'status' => 'Permanent',
                'contact' => '09xxxxxxxxx'
            ],
        ],
    ]);
});

Route::get('/inhouse-nomination', function () {
    return view('forms.inhouse-nomination', [
        'officeName'     => 'City Human Resource Management Office',
        'date'           => 'September 24, 2026',
        'office'         => 'CHRMO',
        'training_title' => 'Sample Training',
        'training_date'  => 'October 5-7, 2026',
        'office_head'    => 'Juan Dela Cruz',
        'participants'   => [
            [
                'surname' => 'Santos',
                'first_name' => 'Maria',
                'mi' => 'L',
                'sex' => 'F',
                'position' => 'Admin Aide',
                'relevance' => '...',
                'status' => 'Permanent',
                'contact' => '09xxxxxxxxx'
            ],
        ],
    ]);
});


Route::get('/attendance', function () {
    return view('forms.attendance');
});



Route::get('/nomination', function () {
    return view('forms.nomination');
});



require __DIR__ . '/auth.php';
