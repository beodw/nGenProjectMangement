<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

use App\Models\Task;
use App\Http\Resources\TaskResource;

use Validator;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::paginate();
        return new TaskResource($tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Get validation rules from task model
        $rules = Task::getValidationRulesForCreation();

        // Validate data and return error if any
        $data = $request->all();

        $validation = Validator::make($data, $rules);
        
        if($validation->stopOnFirstFailure()->fails()){
            return $this->send_response(
                message:'Validation Error',
                status_code:401,
                errors:[$validation->errors()],
            );
        }

        // Create new open task and return id
        $data += ['task_state' => 'open'];
        $data += ['deleted' => false];
        $task = Task::create($data);
        $task = new TaskResource($task);

        return $this->send_response(status_code:201, data: ['id' => $task->id] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $this->send_response(data: new TaskResource($task));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->all();

        $rules = Task::getValidationRulesForUpdate();
        
        $validation = Validator::make($data, $rules);

        if($validation->stopOnFirstFailure()->fails()){
            return $this->send_response(
                status_code:400, 
                message: 'Validation Error', 
                errors: [$validation->errors()]
            );
        }
        $task->update($request->all());

        return $this->send_response(status_code:202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        function dfs($task){
            if($task->deleted){
                return;
            }
            $id = $task->id;
            $task->deleted = true;
            $task->save();
            $tasks = Task::where('parent_task_id', '=', $id)->get();
            foreach($tasks as $task){
                dfs($task);
            }
        }

        dfs($task);
        return $this->send_response();
    }
}