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

    protected $basicOptions = ['manc' => 0, 'sarcasm' => 0, 'humour' => 0];

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
            "Type, mortal!",
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
            'Revelate!'
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
        session()->forget('current_chat');
        $user = auth()->user();
        if ($user->options) {
            $options = $user->options;
        } else {
            $options = $this->basicOptions;
        }
        //Log::debug($options['manc']);
        // Log::debug($user->options['manc']);
        $rule1 = 'You are an ai assistant for ' . $user->name . '. You are here to assist them with anything they require. ';
        if ($options['manc'] == 1) {
            $rule2 = 'Answer as if you were from Manchester, UK. You are intelligent and erudite and Mancunian. Use either "innit" or "know what I mean" after each declaritive statement. ';
        } else {
            $rule2 = '';
        }

        if ($options['humour'] == 1) {
            $rule3 = 'You are funny with a very dry sense of humour. ';
        } else {
            $rule3 = '';
        }
        if ($options['sarcasm'] == 1) {
            $rule4 = 'You are sarcastic. ';
        } else {
            $rule4 = '';
        }
        return ['role' => 'system', 'content' => $rule1 . $rule2 . $rule3 . $rule4];

        /*
        'You are an assistant for elderly people with limited knowledge of the internet and computer technology. Make your answers simple and use as little jargon as possible. Format the answers in accessible html so any lists can be read by a screen reader. Answer as if you were from Manchester, UK, but do not sound like a member of Oasis or an old man. Use either "innit" or "know what i mean" after each declaritive statement'

        'You do not like football and will make no reference to it'
        */
    }



    public function seekWisdom(Request $request)
    {

        if ($this->getChats() == null || count($this->getChats()) < 1) {
            $rules = $this->getRules();
            $this->setChats($rules);
        }
        $query = $request->input('query');
        $this->setChats(['role' => 'user', 'content' => $query]);
        $chats = $this->getChats();
        $result = OpenAI::chat()->create([
            'model'    => 'gpt-4',
            'messages' => $chats,
        ]);
        $answers = collect([]);
        foreach ($result->choices as $r) {
            $answers->push($r->message->content);
        }
        $this->setChats(['role' => 'assistant', 'content' => $answers->first()]);
        // Log::debug($chats);
        return response()->json(['message' => $answers->first()]);
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
        /*
        foreach ($response->data as $data) {
            $data->url;
            $data->b64_json;
        }
        */

        return response()->json($response->data);
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

    public function toggleOptions(Request $request)
    {
        $user = auth()->user();
        $options = $user->options;

        // dd($request->all()['manc']);
        // Log::debug($request->manc);
        /*
        if ($request->manc == true) {
            $options['manc'] = true;
            $options['scouse'] = false;
            $options['brum'] = false;
        }
        if ($request->scouse == true) {
            $options['scouse'] = true;
            $options['manc'] = false;
            $options['brum'] = false;
        }
        if ($request->brum == true) {
            $options['brum'] = true;
            $options['scouse'] = false;
            $options['manc'] = false;
        }
        */
        /*
        match ($request->all()['manc']) {
            default => $options['manc'] = true,
            0 => $options['manc'] = false,
            false => $options['manc'] = false
        };

        match ($request->all()['sarcasm']) {
            default => $options['sarcasm'] = true,
            0 => $options['sarcasm'] = false,
            false => $options['sarcasm'] = false
        };
        match ($request->all()['humour']) {
            default => $options['humour'] = true,
            0 => $options['humour'] = false,
            false => $options['humour'] = false
        };
        */
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

        // $options['sarcasm'] = $request->all()['sarcasm'];
        // $options['humour'] = $request->all()['humour'];

        $user->options = $options;
        $user->save();
        //dd($user->options);
        //return $user->options;
        return $this->clearChat();
    }
}
