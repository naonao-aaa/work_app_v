<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Task;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = request('task_id');
        $comments = Comment::with(['task', 'user', 'files'])->where('task_id', $id)->get();

        return response()->json([
            'comments' => $comments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        if (request('comment')) {
            $comment = Comment::create([
                'task_id' => request('task_id'),
                'user_id' => request('user_id'),
                'text' => request('comment')
            ]);

            $commentId = $comment->id;

            return response()->json([
                'commentId' => $commentId,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function workUserUpdate()
    {
        $userFromToken = auth()->user();   //送られてきたトークンからユーザー情報を取得

        $id = request('task.id');
        $task = Task::find($id);

        //管理者権限ユーザー または タスク登録者 の時に処理を走らせる
        if ($userFromToken->admin == true || $userFromToken->id == $task->admin_user) {

            $task->work_user = request('workUserId');

            $task->save();
        }
    }

    public function statusUpdate()
    {
        $userFromToken = auth()->user();   //送られてきたトークンからユーザー情報を取得

        $id = request('task.id');
        $task = Task::find($id);

        //管理者権限ユーザー または タスク担当者 の時に処理を走らせる
        if ($userFromToken->admin == true || $userFromToken->id == $task->work_user) {

            $task->status_id = request('statusId');

            $task->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
