<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $user;
    public function __construct()
    {
        $this->user = Auth::user();
        view()->share("theme", 'layouts.app');
        if ($this->user != null) {
            view()->share("current_user", $this->user);
            view()->share("current_user_name", $this->user->name);
        }
    }
}
