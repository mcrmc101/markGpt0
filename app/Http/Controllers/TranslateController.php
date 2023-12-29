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

class TranslateController extends Controller
{

    public function showTranslator()
    {
        return Inertia::render('Translator');
    }

    public function translateFrom(Request $request)
    {

        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio')->store('public/files', 'public');

            /*
            $result = OpenAI::audio()->translate([
                'model' => 'whisper-1',
                'file' => Storage::disk('public')->readStream($audioFile),
            ]);
            */
            //Step 1 Transcribe using audio
            $resultTranscribe = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => Storage::disk('public')->readStream($audioFile),
                'language' => 'en',
                'response_format' => 'text',
            ]);

            //Step 2 Translate using GPT
            Storage::disk('public')->delete($audioFile);
            $translateText = 'Translate the following from ' . $request->input('language') . ' into English: ' . $resultTranscribe->text;
            MarkGPT::setChats(['role' => 'user', 'content' => $translateText]);
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
            return response()->json(['message' => $answers->first(), 'originalQuestion' => $translateText]);

            // return $this->seekWisdomQuery($result->text);
        } else {
            return response()->json(['message' => 'No audio file received'], 400);
        }
    }

    public function translateTo(Request $request)
    {

        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio')->store('public/files', 'public');

            /*
            $result = OpenAI::audio()->translate([
                'model' => 'whisper-1',
                'file' => Storage::disk('public')->readStream($audioFile),
            ]);
            */
            //Step 1 Transcribe using audio
            $resultTranscribe = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => Storage::disk('public')->readStream($audioFile),
                'language' => 'en',
                'response_format' => 'text',
            ]);

            //Step 2 Translate using GPT
            Storage::disk('public')->delete($audioFile);
            $translateText = 'Translate the following from English to ' . $request->input('language') . ': ' . $resultTranscribe->text;
            MarkGPT::setChats(['role' => 'user', 'content' => $translateText]);
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
            return response()->json(['message' => $answers->first(), 'originalQuestion' => $translateText]);

            // return $this->seekWisdomQuery($result->text);
        } else {
            return response()->json(['message' => 'No audio file received'], 400);
        }
    }
}
