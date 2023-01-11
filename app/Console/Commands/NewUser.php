<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class NewUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecom:NewUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New User';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('press any digit listed below');
        $i = 1;
        while ($i) {
            $this->info('--------------------------------------------');
            $this->question('1. Run All Migrations');
            $this->question('2. to create new administrator user');
            $this->question('0. for Exit');
            $ask = $this->ask('What is your ans?');
            switch ($ask) {
                case 0:
                    $this->info('Gud Bye......:P');
                    $i = 0;
                    break;
                case 1:
                    $this->call('migrate');
                    break;
                case 2:
                    $name = $this->ask('what is your name?');
                    $email = $this->ask('What is your email?');
                    $password = $this->secret('enter the password?');
                    if ($this->confirm('Are you sure you want to create new User? [yes|no]')) {
                        $this->createUser($name, $email, $password);
                        $this->info('New User created Successfully');
                    }
                    break;
                default:
                    break;
            }
        }


    }

    private function createUser($name, $email, $password)
    {
        try {
            // Create the user
            User::firstOrCreate([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ]);
        } catch (\Exception $exp) {
            $this->info($exp->getMessage());
        }
    }
}
