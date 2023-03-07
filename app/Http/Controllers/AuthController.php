<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Inscription User
    public function register(Request $request)
    {
        //validation des champs
        $attrs= $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'

        ]);

        //create user
        $user = User::create([
            'name'=>$attrs['name'],
            'email'=>$attrs['email'],
            'password'=> bcrypt($attrs['password'])
        ]);

        //Retourne un utilisateur et son token en reponse
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);

    }

    //Utilisateur connecter
     public function login(Request $request)
     {
         //validation des champs
         $attrs= $request->validate([
             'email'=>'required|email',
             'password'=>'required|min:6'
 
         ]);
 
         // Attente de connection
         if(!Auth::attempt($attrs)){
            return response([
                'message'=>'Identifient invalide.'
            ], 403);
         }

         //Retourne un utilisateur et son token en reponse
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
 
     }
        //User deconnecter
        public function logout()
        {
            auth()->user()->tokens()->delete();
            return response([
                'message'=>  'Logout success.'
            ], 200);
        }

        //Obtenir les details de l'utilisateur
        public function user()
        {
            return response([
                'user'=>auth()->user()
            ], 200);
        }
}
