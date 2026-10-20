<?php

use App\Http\Controllers\Portal\LandingController;
use App\Livewire\Candidates\Index as CandidateIndex;
use App\Livewire\Portal\PortalManager;
use App\Livewire\Settings\StageSlaEditor;
use App\Livewire\Vacancies\Index as VacancyIndex;
use App\Livewire\VacancyDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/buzon', [LandingController::class, 'storeFeedback'])->name('feedback.store');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', VacancyDashboard::class)->name('dashboard');

});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/vacantes', VacancyIndex::class)->name('vacancies.index');

    Route::get('/candidatos', CandidateIndex::class)->name('candidates.index');

    Route::get('/admin/sla', StageSlaEditor::class)->name('admin.sla');

    // Contenido del portal (landing). Sin roles por ahora; filtrar por rol en fase 2.
    // TODO: picks can('portal.*') cuando exista Spatie/menú por roles.
    Route::get('/portal/{section}', PortalManager::class)
        ->whereIn('section', ['banners', 'noticias', 'reconocimientos', 'accesos', 'documentos', 'eventos', 'buzon'])
        ->name('portal.manager');

});

require __DIR__.'/settings.php';
