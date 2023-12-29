<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\VoiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/

Route::get('/', [ChatController::class, 'getHomePage'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(ChatController::class)->prefix('chat')->group(function ($router) {
        $router->get('/', 'showTextChat')->name('chat.show');
        $router->post('/seek-wisdom', 'seekWisdom')->name('wisdom.seek');
        $router->get('/get-new-greeting', 'getNewGreeting')->name('greeting.new');
        $router->get('/clear-chat', 'clearChat')->name('chat.clear');
        $router->post('toggle-options', 'toggleOptions')->name('toggle.options');
        $router->post('/create-model', 'createChatModel')->name('model.create');
    });

    Route::controller(ImageController::class)->prefix('images')->group(function ($router) {
        $router->get('/', 'showImageGenerator')->name('image.show');
        $router->post('/create-image', 'createImage')->name('image.create');
    });

    Route::controller(VoiceController::class)->prefix('voice')->group(function ($router) {
        $router->get('/', 'showVoiceChat')->name('voice.show');
        $router->post('/chat-speech', 'chatSpeech')->name('voice.speech');
    });

    Route::controller(TranslateController::class)->prefix('translate')->group(function ($router) {
        $router->get('/', 'showTranslator')->name('translate.show');
        $router->post('/translate-from', 'translateFrom')->name('translate.from');
        $router->post('/translate-to', 'translateTo')->name('translate.to');
    });
});

require __DIR__ . '/auth.php';
