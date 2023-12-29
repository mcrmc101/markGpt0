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

class VoiceController extends Controller
{

    public function showVoiceChat()
    {
        return Inertia::render('VoiceChat');
    }

    public function chatSpeech(Request $request)
    {

        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio')->store('public/files', 'public');

            $result = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => Storage::disk('public')->readStream($audioFile),
                'language' => 'en',
                'response_format' => 'text',
            ]);
            Storage::disk('public')->delete($audioFile);
            return $this->seekWisdomQuery($result->text);
        } else {
            return response()->json(['message' => 'No audio file received'], 400);
        }
    }

    public function seekWisdomQuery($query)
    {

        if (MarkGPT::getChats() == null || count(MarkGPT::getChats()) < 1) {
            $rules = MarkGPT::getRules();
            MarkGPT::setChats($rules);
        }

        MarkGPT::setChats(['role' => 'user', 'content' => $query]);
        $chats = MarkGPT::getChats();
        $result = OpenAI::chat()->create([
            'model'    => 'gpt-4',
            'messages' => $chats,
        ]);
        $answers = collect([]);
        foreach ($result->choices as $r) {
            $answers->push($r->message->content);
        }
        MarkGPT::setChats(['role' => 'assistant', 'content' => $answers->first()]);
        // Log::debug($chats);
        return response()->json(['message' => $answers->first(), 'originalQuestion' => $query]);
    }
}
