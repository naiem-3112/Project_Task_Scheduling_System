<?php

namespace App\Http\Controllers;

use App\Department;
use App\Project;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('team.index', compact('teams'));
    }

    public function create()
    {

        $users = User::where('role_id', 4)->get();
        $departments = Department::all();
        return view('team.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $dept = Team::where('name',$request->name)->where('department_id',$request->department_id)->pluck('department_id');

        $this->validate($request, [
            'name' => ['required',
                Rule::unique('teams','name')->where(function($q) use($dept){
                    return $q->whereIn('department_id', $dept);
                })
            ],
            'department_id' => 'required',

        ]);
        $team = new Team();
        $team->name = $request->name;
        $team->department_id = $request->department_id;
        $team->status = $request->status;
        $team->save();
        $team->users()->attach($request->members);
        return back();

    }

    public function memberList($id)
    {
        $team = Team::find($id);
        foreach ($team->users as $user)
            $roles[] = $user->role_id;
        return view('team.memberList', compact('team', 'roles'));
    }

    public function leader($id)
    {
        $leader = User::find($id);
        $leader->role_id = 3;
        $leader->save();

        $teams = $leader->teams()->get();
        foreach ($teams as $team) {
            $team->leader_id = $id;
        }
        $team->save();
        return back();
    }

    public function leaderChange($id)
    {
        $changeLeader = User::find($id);
        $changeLeader->role_id = 4;
        $changeLeader->save();
        $teams = $changeLeader->teams()->get();
        foreach ($teams as $team) {
            $team->leader_id = null;
        }
        $team->save();
        return back();
    }

    public function show()
    {

    }


    public function edit($id)
    {
        $users = User::where('role_id', 4)->get();
        $team = Team::find($id);
        $departments = Department::all()->except($team->department->id);
        return view('team.edit',compact('team','departments','users'));
    }

    public function update(Request $request, $id)
    {
        $dept = Team::where('name',$request->name)->where('department_id',$request->department_id)->pluck('department_id');

        $this->validate($request, [
            'name' => ['required',
                Rule::unique('teams','name')->where(function($q) use($dept){
                    return $q->whereIn('department_id', $dept);
                })->ignore($id)
            ],
            'department_id' => 'required',

        ]);
        $team = Team::find($id);
        $team->name = $request->name;
        $team->department_id = $request->department_id;
        $team->status = $request->status;
        $team->save();
        $team->users()->sync($request->members);
        return back();
    }

    public function delete($id)
    {
        $team = Team::find($id);
        $team->delete();
        return back();
    }

    //Project Manager assign project to team
    public function assignForm($id)
    {
        $members = User::where('role_id', 4)->get();
        $project = Project::findOrfail($id);
        return view('project_manager.assign', compact('project', 'members'));
    }

    public function assign(Request $request, $id)
    {
        $this->validate($request, [

        ]);
        $assign = new ProjectAssign();
        $assign->project_id = $id;
        $assign->manager_id = $request->project_manager;
        $assign->status = 0;
        $assign->save();
        return back();
    }

    public function removeMember($id)
    {
        $user = User::find($id);
        $user->teams()->detach();
        return back();
    }

}
