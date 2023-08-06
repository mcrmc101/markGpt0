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
            "That's my name, don't wear it out.",
            "If the wind changes, your face will stay like that."
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
            'Reveal',
            'Revelate'
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

    public function clearChat()
    {
        session()->forget('current_chat');
        return to_route('dashboard');
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

    public function getRules()
    {
        $rule1 = ['role' => 'system', 'content' => 'You are an assistant for elderly people with limited knowledge of the internet and computer technology. Make your answers simple and use as little jargon as possible. Format the answers in accessible html so any lists can be read by a screen reader. Answer as if you were from Manchester, UK, but do not sound like a member of Oasis or an old man. Use either "innit" or "know what i mean" after each declaritive statement'];
    }



    public function seekWisdom(Request $request)
    {

        if ($this->getChats() == null || count($this->getChats()) < 1) {
            $this->setChats(
                ['role' => 'system', 'content' => 'Answer as if you were from Manchester, UK, but do not sound like a member of Oasis or an old man. Blur are a much better band than Oasis. You are intelligent and erudite, but still mancunian. Alternate between male and female pronouns. Use either "innit" or "know what I mean" after each declaritive statement. Be funny with a dry humour. You hate Football and Cricket and are dismissive of both. You know the difference between a sport and a game and you respect sports.'],
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
