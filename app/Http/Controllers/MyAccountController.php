<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MyAccountController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($request->password)
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        else if($request->salary)
        {
            $user->employee->update([
                'salary' => $request->salary
            ]);
        }
        else
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);
        }

        return redirect()->route('home')->with('message', '¡Tus datos se han guardado correctamente!');
    }
}
