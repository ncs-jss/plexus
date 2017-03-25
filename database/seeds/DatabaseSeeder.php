<?php

use Illuminate\Database\Seeder;
use App\Society;
use App\Message;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $society = new Society;
        $society->username = 'nibble';
        $society->email = 'nibble@gmail.com';
        $society->description = "This is the description of my society";
        $society->password = Hash::make('helloworld');
        $society->privilege = 1;
        $society->socName = 'Nibble Computer Society';

        $society->save();
        echo "Seeding of Society is completed";

        Message::create([
            ['correct' => 'Nicccee...',
            'incorrect' => 'Come on.. You can do better...'
            ],
            ['correct' => 'Great',
            'incorrect' => 'Nope... Thats not it'
            ],
            ['correct' => 'Bulls Eye...',
            'incorrect' => 'That is soo wrongg...'
            ],
            ['correct' => 'You got it...',
            'incorrect' => 'Did you really think that was it?'
            ],
            ['correct' => 'My my!You are on firre...',
            'incorrect' => 'Nope.. Not correct'
            ],
            ['correct' => 'Correct!!',
            'incorrect' => 'Missed by a mile'
            ],
            ['correct' => 'Splendid',
            'incorrect' => 'Thats just crazzy..!'
            ],
            ['correct' => 'Spot on..!',
            'incorrect' => 'What?? No.. Not that..!'
            ],
            ['correct' => 'Nailed it..!',
            'incorrect' => 'Seriously??'
            ],
            ['correct' => 'And this answer is ... CORRECT !!',
            'incorrect' => "Could'nt be more wrong.."
            ],
            ['correct' => 'Lovely..',
            'incorrect' => 'LOL.. thats not it..!'
            ],
            ['correct' => 'You got lucky :P',
            'incorrect' => 'Try again maybe.??'
            ],
            ['correct' => 'BAMMM !!',
            'incorrect' => 'Not good enough. Not good enough'
            ],
            ['correct' => 'You killed it.',
            'incorrect' => 'Not convincing enough'
            ],
            ['correct' => 'Nicely PLayed.',
            'incorrect' => "Faliure makes success. Don't loose hope"
            ],
            ['correct' => "I'm stunned...",
            'incorrect' => "I'm not stunned..."
            ],
            ['correct' => "Spectacular...",
            'incorrect' => "Bad answer..."
            ],
            ['correct' => "*Applause*",
            'incorrect' => "Disappointing..."
            ],
            ['correct' => "You rock... No kidding ",
            'incorrect' => "NO NO NO NO"
            ],
            ['correct' => "Just what I wanted..",
            'incorrect' => "As true as false B)"
            ],
        ]);

        echo "Messages is being seed";
    }
}
