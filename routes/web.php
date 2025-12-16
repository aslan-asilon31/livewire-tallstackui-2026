<?php

use App\Livewire\User\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index;

Route::view('/', 'welcome')->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/users', Index::class)->name('users.index');

    Route::get('/user/profile', Profile::class)->name('user.profile');
    Route::get('/reports', Profile::class)->name('reports.index');
    Route::get('/employee', Profile::class)->name('employee.index');
    Route::get('/sales', Profile::class)->name('sales.index');
    Route::get('/inventory', Profile::class)->name('inventory.index');
    Route::get('/customer', Profile::class)->name('customer.index');
    Route::get('/procurement', Profile::class)->name('procurement.index');
    Route::get('/finance', Profile::class)->name('finance.index');
});

require __DIR__ . '/auth.php';
