<?php

namespace App\Http\Controllers\Gte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Employee;
use App\ActionType;
use App\Action;
use App\Notifications\ApprovedAction;
use App\Notifications\NoApprovedAction;
use PDF;
use App\Notifications\NewPersonalAction;

use App\Http\Resources\ActionResource;


class PersonalActionGteController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::id());
        if($user->hasPermission('browse_actions'))
        {
            if(!$user->firm)
            {
                return redirect()->route('employees.editfirm', $user->id);
            }

            return view('boss.actions-index', compact('user'));
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Display form for create new personal action
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if(!$user->hasPermission('browse_actions')){
            return back()->with([
                'message'   => 'No tienes permiso para crear una acción de personal',
                'type'      => 'warning'
            ]);
        }

        $typeactions = ActionType::all();
        $employees = $user->workersGte()->orderBy('name_employee','ASC')->get();
        if(!$user->firm){
            return redirect()->route('employees.edit', $user->id)->with('message', 'Para crear una accón de personal debes tener una firma');
        }

        if($request->employee)
        {
            $employee = $request->employee;
            return view('boss.new-personal-action', compact('user','typeactions','employee','employees'));
        }

        return view('boss.new-personal-action', compact('user','typeactions','employees'));
    }

    /**
     * Return a listing of actions pendings.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showPendings()
    {   
        $gte = User::find(Auth::id());

        $personal_actions = $gte->workersGte()->select('actions.*','users.name')
                            ->join('users','users.id','employees.user_id')
                            ->join('actions','actions.created_by','users.id')
                            ->orderBy('created_at','DESC')
                            ->whereNull('check_gte')
                            ->orderBy('created_at','DESC')
                            ->paginate(25);

        return response()->json([
            'actions' => ActionResource::collection($personal_actions),
        ],200);
    }

    /**
     * Return a listing of actions approved.
     * 
     * @return \Illuminate\Http\Response
     */

     public function showApproved()
     {
        $gte = User::find(Auth::id());

        $personal_actions = $gte->workersGte()->select('actions.*','users.name')
                        ->join('users','users.id','employees.user_id')
                        ->join('actions','actions.created_by','users.id')
                        ->orderBy('created_at','DESC')
                        ->where('check_gte','=',1)
                        ->orderBy('created_at','DESC')
                        ->get();

        $personal_actionsTwo = $gte->actionsEmp()->where('check_gte',1)->get();
        $personal_actions = $personal_actions->merge($personal_actionsTwo);

        $personal_actions = $personal_actions->sortByDesc('created_at');

        return response()->json([
            'actions' => ActionResource::collection($personal_actions),
        ],200);
     }

     /**
     * Return a listing of actions not approved.
     * 
     * @return \Illuminate\Http\Response
     */

    public function showNotApproved()
    {
        $gte = User::find(Auth::id());

        $personal_actions = $gte->workersGte()->select('actions.*','users.name')
                            ->join('users','users.id','employees.user_id')
                            ->join('actions','actions.created_by','users.id')
                            ->orderBy('created_at','DESC')
                            ->where('check_gte','=',0)
                            ->orderBy('created_at','DESC')
                            ->paginate(25);

        return response()->json([
            'actions' => ActionResource::collection($personal_actions),
        ],200);
    }

    /**
     * Update the specified action (approve) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function approveAction(Request $request, $id)
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

        $employee = $personal_action->user;

        $personal_action->update(['check_gte' => 1]);

        $employee->notify(new ApprovedAction($user->name));

        $rh = $user->companiesResources()->first();
        $rh = User::select('users.*')->where('role_id',3)->join('company_resources','company_resources.user_id','users.id')->where('company_resources.company_id',$rh->company_id)->first();
        $rh->notify(new NewPersonalAction($employee));

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

     public function notApproveAction(Request $request, $id)
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

        $employee = $personal_action->user;
        
        $personal_action->update(['check_gte' => 0, 'comments' => $request->comments]);

        $employee->notify(new NoApprovedAction($user, $request->comments, $personal_action));

        return response()->json([
            'message'   => '¡Tus cambios se han guardado correctamente!',
            'icon'      => 'success'
        ]);
     }

}
