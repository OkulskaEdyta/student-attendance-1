<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home', ['title' => 'Gestion des présences'])->name('pages.home');
Route::view('/courses', 'courses.index', ['title' => 'Gestion des présences'])->middleware('auth')->name('courses.index');
Route::view('/courses/{course}', 'courses.show', ['title' => 'Gestion des présences'])->middleware('auth');
Route::view('/lessons/{lesson}/attendances', 'attendances.edit', ['title' => 'Gestion des présences'])->middleware('auth');

//Route::view('/logincreate', 'auth.logincreate', ['title' => 'Gestion des présences']);
