<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Launcher\Mercurius\Facades\Mercurius;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return redirect($user->slug.'/messages');
    }

    public function userMessages() {
        $user = auth()->user();

        return redirect($user->slug.'/messages');
//        return redirect('messages');
    }

    public function signin(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = Mercurius::user()
                ->where('email', $request->email)
                ->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    return redirect($user->slug.'/messages');
                }
            }

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }
        return view('auth.signin');
    }

    public function signup(Request $request, UserRepository $user)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $slug = str_slug($request->name, '_');
            if ($user->find($slug)) {

                $slug = $this->incrementSlug($slug, $user);
            }
            User::create([
                'name' => $request->name,
                'slug' => $slug,
                'email' =>  $request->email,
                'password' => Hash::make( $request->password),
            ]);

            return redirect('signin');
        }
        return view('auth.signup');
    }

    public function incrementSlug($slug, UserRepository $user) {
        $original = $slug;
        $count = 2;

        while ($user->find($slug)) {
            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

}
