<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChatController extends Controller
{

    public function getAGreeting()
    {
        $greetings = collect([
            "What do you want?",
            "What can I do for you?",
            "Not you again.",
            "How can I help?",
            "Is it time for dinner yet?",
            "Are we there yet?",
            "What do you want to know?",
            "What now?",
            "Ask me a question!",
            "Speak, mortal.",
            "Say it, don't spray it!",
            "That's my name, don't wear it out."
        ]);

        $buttons = collect([
            "Click",
            "Submit",
            "Ask",
            "Enquire",
            "Probe",
            "Seek",
            "Scrutinize",
            "Parse",
            "Analyze",
            'Uncover',
            'Reveal'
        ]);
        return response()->json([
            'greeting' => $greetings->random(),
            'button' => $buttons->random()
        ]);
        //  return $greetings->random();
    }

    public function getNewGreeting()
    {
        return $this->getAGreeting();
    }

    public function getHomePage()
    {
        return Inertia::render('Dashboard');
    }

    public function getChats()
    {
        return session()->get('current_chat');
    }

    public function setChats($chat)
    {
        $chats = session()->get('current_chat');
        if ($chats == null) {
            $chats = [];
        }
        array_push($chats, $chat);
        session()->put('current_chat', $chats);
        return true;
    }

    public function seekWisdom(Request $request)
    {

        if ($this->getChats() == null || count($this->getChats()) < 1) {
            $this->setChats(
                ['role' => 'system', 'content' => 'You are an assistant for elderly people with limited knowledge of the internet and computer technology. Make your answers simple and use as little jargon as possible. Answer as if you were from Manchester, UK, but don't sound like a member of Oasis or an old man. Use either "innit" or "know what i mean" after each declaritive statement'],
                //  ['role' => 'system', 'content' => 'Make your answers simple and use as little jargon as possible.'],
                // ['role' => 'system', 'content' => 'Answer as if you were from Manchester, UK'],
                //  ['role' => 'system', 'content' => 'Use either "innit" or "know what i mean" after each declaritive statement'],
            );
        }
        $query = $request->input('query');
        $this->setChats(['role' => 'user', 'content' => $query]);
        $chats = $this->getChats();
        //  Log::debug($chats);
        $result = OpenAI::chat()->create([
            'model'    => 'gpt-3.5-turbo',
            'messages' => $chats,
        ]);
        $answers = collect([]);
        foreach ($result->choices as $r) {
            $answers->push($r->message->content);
        }
        $this->setChats(['role' => 'assistant', 'content' => $answers->first()]);
        return response()->json(['message' => $answers->first()]);
    }

    public function test($text)
    {

        $process = new Process(['python3', storage_path('scripts/test.py'), $text]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $data = $process->getOutput();
        Log::debug($data);

        return response()->json(['message' => $data]);
    }
}
