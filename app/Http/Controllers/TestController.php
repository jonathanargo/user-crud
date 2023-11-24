<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\ManualUser;

class TestController extends Controller
{
    public function index()
    {
        $user = ManualUser::find(6);

        return Response('test');
    }
}
