<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);
        return redirect(route('users.index')); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect(route('users.index'));
    }

    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }

    public function actionEdit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user
        ]);
    }

    public function actionUpdate(User $user, UpdateUserRequest $request)
    {
        $user->update(request()->all());
        return redirect(route('users.index'));
    }

}
