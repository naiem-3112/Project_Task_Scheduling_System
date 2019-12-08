<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(){
        if(Auth::user()->role_id == 4){
            
            return view('task.index');
        }
    }

    public function assignForm($id)
    {
        $project = Project::find($id);
        return view('task.assign', compact('project'));
    }

    public function assign(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required',
            'tasks' => 'required',
            'description' => 'required_without:files',
            'files' => 'required_without:description'
        ]);
        $task = new Task();
        $task->project_id = $id;
        $task->member_id = $request->member_id;
        $task->description = $request->description;
        if ($request->file('files'))
        {
            foreach ($request->file('files') as $file){
                $ext = $id.'.'.$file->getClientOriginalExtension();
                $path = public_path('files/tasks');
                $file->move($path, $ext);
                $task->filename = $ext;
            }
        }

        $task->save();
        $task->requirements()->attach($request->tasks);
        return back();

    }
}
