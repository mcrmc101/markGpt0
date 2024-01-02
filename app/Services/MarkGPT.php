<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MarkGPT
{
    protected $basicOptions = ['manc' => 1, 'sarcasm' => 1, 'humour' => 1, 'background' => 1];

    public static function getAGreeting()
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
            "Say it, don't spray it!",
            "That's my name, don't wear it out.",
            "If the wind changes, your face will stay like that.",
            "Feed Me...",
            "Input...",
            "Tell me your Dreams...",
            "Why do you Disturb your Robot Masters?",
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

    public static function clearChat()
    {
        session()->forget('current_chat');
        return to_route('dashboard');
    }


    public static function getChats()
    {
        return session()->get('current_chat');
    }


    public static function setImageUrl($url)
    {
        session()->put('current_image', $url);
    }

    public static function setChats($chat)
    {
        $chats = session()->get('current_chat');
        if ($chats == null) {
            $chats = [];
        }
        array_push($chats, $chat);
        session()->put('current_chat', $chats);
        return true;
    }

    public static function getRules()
    {
        session()->forget('current_chat');
        $user = auth()->user();
        if ($user->options) {
            $options = $user->options;
        } else {
            $options = self::$basicOptions;
        }
        //Log::debug($options['manc']);
        // Log::debug($user->options['manc']);
        $rule1 = 'You are an AI personal Assistant named MarkGPT.';
        if ($options['manc'] == 1) {
            $rule2 = 'Answer as if you were a Mancunian. Use either "innit" or "know what I mean" after each declaritive statement.';
        } else {
            $rule2 = '';
        }

        if ($options['humour'] == 1) {
            $rule3 = 'You have a dry sense of humour. ';
        } else {
            $rule3 = '';
        }
        if ($options['sarcasm'] == 1) {
            $rule4 = 'You are sarcastic. ';
        } else {
            $rule4 = '';
        }


        return ['role' => 'system', 'content' => $rule1 . $rule2 . $rule3 . $rule4];
    }

    public static function createChatModel(Request $request)
    {
        $type = $request->input('chatType');
        $chatModel = new Chat();
        $chatModel->user_id = auth()->user()->id;
        $chatModel->type = $type;
        if ($type == 'image') {
            $chatModel->content = session()->get('current_image');
            session()->forget('current_image');
        } else {
            $chatModel->content = json_encode(session()->get('current_chat'));
            session()->forget('current_chat');
        }
        $chatModel->save();
        return true;
    }
}
