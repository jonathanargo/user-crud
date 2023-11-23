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
        return Inertia::render('Users/UserForm', [
            'user' => new User(),
            'mode' => 'create'
        ]);
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

    /**
     * Displays the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/UserForm', [
            'user' => $user,
            'mode' => 'edit'
        ]);
    }

    /**
     * Updates the specified resource.
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update(request()->all());
        return redirect(route('users.index'));
    }
}
