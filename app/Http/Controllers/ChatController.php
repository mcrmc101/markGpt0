<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{

    public function getAGreeting()
    {
        $greetings = collect([
            'What do you want?',
            'What can I do for you?',
            'Not you again.',
            'How are you, pal?',
            'Are you well, mate?'
        ]);
        return $greetings->random();
    }

    public function getNewGreeting()
    {
        return $this->getAGreeting();
    }

    public function getHomePage()
    {
        return Inertia::render('Dashboard');
    }


    public function seekWisdom(Request $request)
    {
        $query = $request->input('query');

        $yourApiKey = env('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);

        /*
        $response = $client->completions()->create([
            'model' => 'gpt-3.5-turbo',
            'prompt' => 'Say this is a test',
            'max_tokens' => 6,
            'temperature' => 0
        ]);
        */
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'PHP is',
            'temperature' => 0
        ]);


        Log::debug($result['choices'][0]['text']);


        return response()->json(['message' => $result['choices'][0]['text']]);
    }
}
