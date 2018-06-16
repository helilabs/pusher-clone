<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Console\Command;


class TokenCreatorCommand extends Command{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new tokens for a new app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $app_id = Str::uuid();
        $app_secret = str_random(32);

        Token::create([
            'app_id' => $app_id,
            'secret' => $app_secret
        ]);

        $this->table([
            'app_id',
            'secret'
        ],[
            [$app_id, $app_secret]
        ]);
    }


}