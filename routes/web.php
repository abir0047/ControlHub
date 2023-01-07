<?php

use App\Http\Controllers\Admin\OrderProcessController;
use App\Http\Controllers\QuestionRelated\ExamCategoriesController;
use App\Http\Controllers\QuestionRelated\ExamGroupController;
use App\Http\Controllers\QuestionRelated\QuestionController;
use App\Http\Controllers\QuestionRelated\QuestionSetController;
use App\Http\Controllers\QuestionRelated\SectionController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// SectionController
Route::get('/section', [SectionController::class, 'index'])->name('section.index');
Route::delete('/section/{id}', [SectionController::class, 'destroy'])->name('section.delete');
Route::get('/section/add', [SectionController::class, 'add'])->name('section.add');
Route::post('/section/add', [SectionController::class, 'store'])->name('section.store');
Route::post('/section/conform_update/{id}', [SectionController::class, 'updateView'])->name('section.update.view');
Route::post('/section/update', [SectionController::class, 'update'])->name('section.update');

// ExamCategoriesController
Route::get('/exam-categories', [ExamCategoriesController::class, 'index'])->name('exam-categories.index');
Route::get('/exam-categories/add', [ExamCategoriesController::class, 'add'])->name('exam-categories.add');
Route::post('/exam-categories/add', [ExamCategoriesController::class, 'store'])->name('exam-categories.store');
Route::delete('/exam-categories/delete', [ExamCategoriesController::class, 'destroy'])->name('exam-categories.delete');
Route::post('/exam-categories/update', [ExamCategoriesController::class, 'updateView'])->name('exam-categories.update.view');
Route::post('/exam-categories/confirm_update', [ExamCategoriesController::class, 'update'])->name('exam-categories.update');


// ExamGroupController
Route::get('/exam-group', [ExamGroupController::class, 'index'])->name('exam-group.index');
Route::post('/exam-group', [ExamGroupController::class, 'search'])->name('exam-group.search');
Route::get('/exam-group/add', [ExamGroupController::class, 'add'])->name('exam-group.add');
Route::post('/exam-group/add', [ExamGroupController::class, 'store'])->name('exam-group.store');
Route::delete('/exam-group/delete', [ExamGroupController::class, 'destroy'])->name('exam-group.delete');
Route::post('/exam-group/edit', [ExamGroupController::class, 'edit'])->name('exam-group.edit');
Route::post('/exam-group/update', [ExamGroupController::class, 'update'])->name('exam-group.update');

// QuestionSetController
Route::get('/question-set', [QuestionSetController::class, 'index'])->name('question-set.index');
Route::post('/question-set', [QuestionSetController::class, 'search'])->name('question-set.search');
Route::get('/question-set/add', [QuestionSetController::class, 'add'])->name('question-set.add');
Route::post('/question-set/fetch', [QuestionSetController::class, 'fetch'])->name('question-set.fetch');
Route::post('/question-set/add', [QuestionSetController::class, 'store'])->name('question-set.store');
Route::delete('/question-set/delete', [QuestionSetController::class, 'destroy'])->name('question-set.delete');
Route::get('/question-set/edit', [QuestionSetController::class, 'edit'])->name('question-set.edit');
Route::post('/question-set/update', [QuestionSetController::class, 'update'])->name('question-set.update');

// QuestionController
Route::get('/question', [QuestionController::class, 'index'])->name('question.index');
Route::post('/question', [QuestionController::class, 'search'])->name('question.search');
Route::get('/question/add', [QuestionController::class, 'add'])->name('question.add');
Route::post('/question/fetch', [QuestionController::class, 'fetch'])->name('question.fetch');
Route::post('/question/add', [QuestionController::class, 'store'])->name('question.store');
Route::delete('/question/delete', [QuestionController::class, 'destroy'])->name('question.delete');
Route::get('/question/edit', [QuestionController::class, 'edit'])->name('question.edit');
Route::post('/question/update', [QuestionController::class, 'update'])->name('question.update');

// OrderProcessController
Route::get('/check-order', [OrderProcessController::class, 'index'])->name('order.index');
Route::post('/process-order', [OrderProcessController::class, 'process'])->name('order.process');
Route::post('/processed-order', [OrderProcessController::class, 'workLoad'])->name('order.workLoad');
Route::get('/order-list', [OrderProcessController::class, 'orderList'])->name('order.orderList');
Route::post('/remove-access', [OrderProcessController::class, 'removeAccess'])->name('order.removeAccess');

require __DIR__ . '/auth.php';
