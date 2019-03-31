<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $post_id)
    {
        $response = new JsonResponse();
        $post = Post::find($post_id);
        if($post == null){
            $response->setStatusCode(404, 'Post not found');
            $response->setJson(json_encode(array('message' => 'Post not found')));
            return $response;
        }
        if($request->bearerToken() != null){
            $request['author'] =  'admin';
        }
        $validator = Validator::make($request->all(), [
            'author' => 'required',
            'comment' => 'required|max:255',
        ]);
        if(!$validator->fails())
        {
            $post->comments()->save( new Comment([
                'author' => $request['author'],
                'comment' => $request['comment'],
                'datatime' => date('H:i d.m.Y')
            ]));
            $response->setStatusCode(201, 'Successful creation');
            $response->setJson(json_encode(array('status' => true)));
            return $response;
        }
        else
        {
            $response->setStatusCode(400, 'Creating error');
            $response->setJson(json_encode(array('status' => false, 'message' => $validator->errors())));
            return $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $post_id , $comment_id)
    {
        $response = new JsonResponse();
        $post = Post::find($post_id);
        $comment = Comment::find($comment_id);
        if($post == null){
            $response->setStatusCode(404, 'Post not found');
            $response->setJson(json_encode(array('message' => 'Post not found')));
            return $response;
        }
        else if($comment == null){
            $response->setStatusCode(404, 'Comment not found');
            $response->setJson(json_encode(array('message' => 'Comment not found')));
            return $response;
        }
        else{
            $comment->delete();
            $response->setJson(json_encode(array("status" => true)));
            $response->setStatusCode(201, "Successful delete");
            return $response;
        }
    }
}
