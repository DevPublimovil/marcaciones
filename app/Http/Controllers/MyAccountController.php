<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class MyAccountController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$user->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }else{
            if($request->password){
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
            }else{
                 $user->update([
                     'name' => $request->name,
                     'email' => $request->email,
                 ]);
            }
     
            if($request->salary){
                $user->employee->update([
                    'salary' => $request->salary
                ]);
            }
     
            return back()->with('message','Tus datos se actualizaron con éxito');
        }
    }

    public function index()
    {
        $user = User::find(Auth::id());
        return view('employees.edit-profile',compact('user'));
    }

    public function show()
    {
        $user = User::find(Auth::id());
        return $user;
    }
}
