<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of Task per Project
     *
     * @param int $id
     * @return Response
     */
    public function index(int $id): Response
    {
        $project = Project::find($id);
        if (!$project || !$this->validateProject($project)) {
            return \response(['message' => 'Invalid ID'], 401);
        }

        return \response(['tasks' => $project->tasks]);
    }

    /**
     * Create new Task
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function store(Request $request, int $id): Response
    {
        $project = Project::find($id);
        if (!$project || !$this->validateProject($project)) {
            return \response(['message' => 'Invalid ID'], 401);
        }

        $fields = $request->validate([
            'description' => 'required|string',
        ]);

        $task = new Task();
        $task->description = $fields['description'];
        $task->isCompleted = false;
        $task->viewCount = 0;
        $task->project()->associate($project);
        $task->save();

        return \response(['task' => $task]);
    }

    /**
     * Display the specified Task
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $task = Task::find($id);
        if (!$task || !$this->validateTask($task)) {
            return \response(['message' => 'Invalid ID'], 401);
        }

        $task->viewCount += 1;
        $task->save();

        return \response(['task' => $task]);
    }

    /**
     * Change Task completion status
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $task = Task::find($id);
        if (!$task || !$this->validateTask($task)) {
            return \response(['message' => 'Invalid ID'], 401);
        }

        $fields = $request->validate([
            'isCompleted' => 'required|boolean'
        ]);

        $task->isCompleted = $fields['isCompleted'];
        $task->save();

        return \response(['message' => 'Task updated']);
    }

    /**
     * Remove Task
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $task = Task::find($id);
        if (!$task || !$this->validateTask($task)) {
            return \response(['message' => 'Invalid ID'], 401);
        }

        return \response(['deleted' => Task::destroy($id)]);
    }

    /**
     * Checks if Project belongs to current User
     * ToDo - move to service
     *
     * @param Project $project
     * @return bool
     */
    private function validateProject(Project $project): bool
    {
        return $project->user->id === auth()->user()->id;
    }

    /**
     * Checks if Task belongs to current User
     * ToDo - move to service
     *
     * @param Task $task
     * @return bool
     */
    private function validateTask(Task $task): bool
    {
        return $this->validateProject($task->project);
    }
}
