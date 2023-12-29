<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\MarkGPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImageController extends Controller
{


    public function showImageGenerator()
    {
        return Inertia::render('ImageGenerator');
    }

    public function createImage(Request $request)
    {
        $query = $request->input('query');
        $size = $request->input('size');
        $quality = $request->input('quality');
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $query,
            'n' => 1,
            'size' => $size,
            'quality' => $quality,
            'response_format' => 'url',
        ]);

        foreach ($response->data as $data) {
            MarkGPT::setImageUrl($data->url);
        }
        return response()->json($response->data);
    }
}
