<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\ManualUser;

class TestController extends Controller
{
    public function index()
    {
        $this->purgeRecords();
        $user = ManualUser::find(6);
        $user->first_name = '';
        $result = $user->save();
        dump($user->toArray());
        dd($result);
    }

    private function updateUser()
    {
        $user = ManualUser::find(6);
        dump("user before", $user->toArray());
        $user->first_name = 'Foo'.rand(0, 100);
        $user->save();
        dd($user->toArray());
    }

    private function createUser()
    {
        $user = new ManualUser();
        $user->first_name = 'John';
        $user->last_name = 'Doe';
        $user->email = 'test@test.com';
        $user->mobile_number = '123-456-7890';
        $user->address = '123 Test St';
        $user->city = 'Testville';
        $user->state = 'SC';
        $user->zip = 12345;
        $user->country = 'us';
        $user->timezone = '';

        $result = $user->save();
        dd('final attributes', $user->toArray());
    }

    private function purgeRecords()
    {
        $users = ManualUser::findAll();
        foreach ($users as $user) {
            if ($user->id > 6) {
                $user->delete();
            }
        }
    }
}
