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

class ChatController extends Controller
{




    public function getNewGreeting()
    {
        return MarkGPT::getAGreeting();
    }

    public function getHomePage()
    {
        return Inertia::render('Dashboard');
    }

    public function clearChat()
    {
        return MarkGPT::clearChat();
    }

    public function createChatModel(Request $request)
    {
        return MarkGPT::createChatModel($request);
    }

    public function setImageUrl($url)
    {
        return MarkGPT::setImageUrl($url);
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

    public function seekWisdom(Request $request)
    {
        // Log::debug($request);
        if (MarkGPT::getChats() == null || count(MarkGPT::getChats()) < 1) {
            $rules = MarkGPT::getRules();
            MarkGPT::setChats($rules);
        }
        $query = $request->input('query');
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
        return response()->json(['message' => $answers->first()]);
    }

    public function seekWisdomQuery($query)
    {

        if (MarkGPT::getChats() == null || count($this->getChats()) < 1) {
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



    public function toggleOptions(Request $request)
    {
        $user = auth()->user();
        $options = $user->options;

        $manc = $request->all()['manc'];
        if ($manc == 1 || $manc == null) {
            $manc = 1;
        }
        if ($manc == 0) {
            $manc = 0;
        }
        $options['manc'] = $manc;

        $sarcasm = $request->all()['sarcasm'];
        if ($sarcasm == 1 || $sarcasm == null) {
            $sarcasm = 1;
        }
        if ($sarcasm == 0) {
            $sarcasm = 0;
        }
        $options['sarcasm'] = $sarcasm;

        $humour = $request->all()['humour'];
        if ($humour == 1 || $humour == null) {
            $humour = 1;
        }
        if ($humour == 0) {
            $humour = 0;
        }
        $options['humour'] = $humour;

        $background = $request->all()['background'];
        if ($background == 1 || $background == null) {
            $background = 1;
        }
        if ($background == 0) {
            $background = 0;
        }
        $options['background'] = $background;
        $user->options = $options;
        $user->save();
        return MarkGPT::clearChat();
    }
}
