<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:setpasswd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a new tmp password for user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Enter user e-mail: ');
        $tempPassword = $this->ask('Enter temparary password: ');
        $user = User::query()->where('email', '=', $email)->first();
        $user->password = Hash::make($tempPassword);
        $user->password_changed_at = null;
        $user->save();
        return 0;
    }
}
