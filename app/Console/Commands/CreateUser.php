<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating user and init into users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $exception) {
            $this->error('no connection with database!');
            return;
        }

        $username = text(
            label: 'Type a username',
            default: 'merchant',
            required: true,
            validate: function (string $val): string|null {
                return User::where('username', $val)->exists() ? 'username is exists!' : null;
            }
        );

        $password = password(
            label: 'Type a password',
            required: true,
            validate: function (string $val): string|null {
                return strlen($val) >= 6 ? null : 'password should contain at least 6 symbols!';
            }
        );

        User::create([
            'username' => $username,
            'password' => bcrypt($password)
        ]);

        $this->info('user has been created!');
    }
}
