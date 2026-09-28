<?php

namespace App\Http\Controllers\Portal;

use App\Application\Portal\UseCases\ListPublishedContentUseCase;
use App\Application\Portal\UseCases\SubmitFeedbackUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Infrastructure (entrada HTTP): sin lógica de negocio,
 * solo delega a los casos de uso y devuelve la vista.
 */
class LandingController extends Controller
{
    public function index(Request $request, ListPublishedContentUseCase $useCase)
    {
        $search = $request->query('search');
        $categoria = $request->query('categoria', 'todos');

        $content = $useCase->handle($search ?: null, $categoria ?: null);

        return view('welcome', [
            'search' => $search,
            'categoria' => $categoria,
            'banners' => $content['banners'],
            'noticiasCorporativas' => collect($content['news']),
            'recognitions' => $content['recognitions'],
            'quickLinks' => $content['quickLinks'],
            'documents' => $content['documents'],
            'events' => $content['events'],
        ]);
    }

    public function storeFeedback(Request $request, SubmitFeedbackUseCase $useCase)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3|max:2000',
            'anonimo' => 'nullable|boolean',
        ]);

        try {
            $useCase->handle($validated['body'], (bool) ($validated['anonimo'] ?? false));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['body' => $e->getMessage()])->withInput();
        }

        return back()->with('feedback_ok', '¡Comentario enviado con éxito! Gracias por tu retroalimentación.');
    }
}
