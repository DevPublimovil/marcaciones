<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Action;
use App\Notifications\ApprovedAction;
use App\Notifications\NoApprovedAction;

class PersonalActionRhController extends Controller
{
    /**
     * Update the specified action (approve) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function approve(Request $request, $id)
    {
       $user = User::find(Auth::id());

       if(!$user->hasPermission('edit_actions'))
       {
           return response()->json([
               'message'  => 'No tienes permisos para aprobar la acción de personal',
               'icon'     => 'warning'
           ]);
       }

       $personal_action = Action::find($id);

       $employee = ($personal_action->employee_id)  ?  $personal_action->employee->user : $personal_action->user;

       $personal_action->update(['check_rh' => 1]);

       $employee->notify(new ApprovedAction($user->name));

       return response()->json([
           'message'   => '¡Tus cambios se han guardado correctamente!',
           'icon'      => 'success'
       ]);
    }

    /**
    * Update the specified action (not approve) in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function notApprove(Request $request, $id)
    {
       $user = User::find(Auth::id());
       
       /* if(!$user->hasPermission('edit_actions'))
       {
           return response()->json([
               'message'  => 'No tienes permisos para aprobar la acción de personal',
               'icon'     => 'warning'
           ]);
       } */

       $personal_action = Action::find($id);
       
       $personal_action->update(['check_rh' => 0, 'comments' => $request->comments]);
       
       if($personal_action->user->role->name == 'empleado'){
            $personal_action->user->notify(new NoApprovedAction($user, $request->comments,  $personal_action));
            $personal_action->user->employee->jefe->notify(new NoApprovedAction($user, $request->comments,  $personal_action));
        }else{
            if($personal_action->employee_id != null){
                if($personal_action->employee->type_employee == 1){
                    $personal_action->employee->user->notify(new NoApprovedAction($user, $request->comments,  $personal_action));
                }
            }
            $personal_action->user->notify(new NoApprovedAction($user, $request->comments,  $personal_action));
        }

       return response()->json([
           'message'   => '¡Tus cambios se han guardado correctamente!',
           'icon'      => 'success'
       ]);
    }
}
