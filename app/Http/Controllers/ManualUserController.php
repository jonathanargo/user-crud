<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualUser;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ManualUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = ManualUser::findAll();
        $userData = [];
        foreach ($users as $user) {
            $userData[] = $user->toArray();
        }
        return Inertia::render('Users/Index', [
            'users' => $userData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/UserForm', [
            'user' => new ManualUser(),
            'mode' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new ManualUser($request->all());
        if ($user->save()) {
            return redirect(route('users.index'));
        } else {
            $errors = $user->getErrors();
            return Inertia::render('Users/UserForm', [
                'user' => $user->toArray(),
                'mode' => 'create',
                'errors' => $errors,
                'showErrorAlert' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $userId)
    {
        $user = ManualUser::find($userId);
        if ($user) {
            $user->delete();
        }
        return redirect(route('users.index'));
    }

    /**
     * Displays the specified resource.
     */
    public function show(Request $request, int $userId)
    {
        $user = ManualUser::find($userId);
        return Inertia::render('Users/Show', [
            'user' => $user->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $userId)
    {
        $user = ManualUser::find($userId);
        return Inertia::render('Users/UserForm', [
            'user' => $user->toArray(),
            'mode' => 'edit',
            'showErrorAlert' => false
        ]);
    }

    /**
     * Updates the specified resource.
     */
    public function update(Request $request, int $user)
    {
        $user = ManualUser::find($user);
        $user->assign($request->all());

        if ($user->save()) {
            return redirect(route('users.index'));
        } else {
            $errors = $user->getErrors();
            return Inertia::render('Users/UserForm', [
                'user' => $user->toArray(),
                'mode' => 'edit',
                'errors' => $errors,
                'showErrorAlert' => false
            ]);
        }
    }
}
