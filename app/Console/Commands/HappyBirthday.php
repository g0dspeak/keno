<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class HappyBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Happy birthday message to users via SMS';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = new \stdClass();
        $user->email = 'alexxx.tsyk@gmail.com';
        $user->name = 'Alex';

        try {
            Mail::send('emails.happy_birthday', ['user' => $user], function ($m) use ($user) {
                $m->from('hello@app.com', 'Your Application');

                $m->to($user->email, $user->name)->subject('Dear Alex, I wish you a happy birthday!');
            });
        } catch (\Exception $e) {
            dd($e);
        }

        $this->info('The happy birthday messages were sent successfully!');
    }
}
