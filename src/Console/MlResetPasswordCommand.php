<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;

class MlResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for a specific admin user';

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
     * @return mixed
     */
    public function handle()
    {
        //

        $userModel = config('admin.database.users_model');

        $users = $userModel::where('bool_admin',1)->get();

        askForUserName:
        $username = $this->askWithCompletion('Please enter a username who needs to reset his password', $users->pluck('username')->toArray());

        $this->info($username);

        $user = $users->first(function ($user) use ($username) {
            return $user->username == $username;
        });

        if (is_null($user)) {
            $this->error('The user you entered is not exists !!!');
            goto askForUserName;
        }

        enterPassword:
        $password = $this->secret('Please enter a password');

        if ($password !== $this->secret('Please confirm the password')) {
            $this->error('The passwords entered twice do not match, please re-enter');
            goto enterPassword;
        }

        $user->password = bcrypt($password);

        $user->save();

        $this->info('[' . date('Y/m/d H:i:s') . '] ' .'User ['.$username.'] password reset successfully.');

    }
}
