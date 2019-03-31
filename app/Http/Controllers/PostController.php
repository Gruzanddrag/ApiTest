<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //TODO: make a one method ALL POSTS and ONE POST
        $response = new JsonResponse();
        $posts = Post::all();
        $json = json_encode($posts);
        $response->setJson($json);
        $response->setStatusCode(200, 'List posts');
        return $response;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts',
            'anons' => 'required',
            'text' => 'required',
            'image' => 'required|max:2048|image',
        ]);
        $response = new JsonResponse();
        if(!$validator->fails()){
            $post = new Post;
            $post->fill($request->all());
            $file = $request->file('image');
            $post->image = $request->root() . '/api/post_images/' . $file->getClientOriginalName();
            $file->storeAs('/post_images', $file->getClientOriginalName());
            $post->tags = explode(',',$request['tags']);
            $post->datatime = date('H:i d.m.Y');
            $post->save();
            $response->setJson( json_encode( array( 'status' => 'true', 'post_id' => $post->id) ) );
            $response->setStatusCode(201, 'Successful creation');
            return $response;
        }
        else{
            $response->setJson( json_encode( array( 'status' => false, 'message' => $validator->errors()) ) );
            $response->setStatusCode(400, 'Creating error');
            return $response;
        }

    }

    /**
     * Store a newly created resource in api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByTag(Request $request, $tagName)
    {
        $posts = Post::where('tags', 'like', '%'. $tagName .'%')->get();
        $response = new JsonResponse();
        $response->setStatusCode(200, 'Found posts');
        $response->setJson(json_encode($posts));
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , $id)
    {
        $response = new JsonResponse();
        $post = Post::find($id);
        if($post == null){
            $response->setStatusCode(404, 'Post not found');
            $response->setJson(json_encode(array('message' => 'Post not found')));
            return $response;
        }
        $post['comments'] = $post->comments;
        $json = json_encode($post);
        $response->setJson($json);
        $response->setStatusCode(200, 'View post');
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $post = Post::find($id);
        if($post == null || $post == ""){
            $response = new JsonResponse();
            $response->setJson(json_encode(array("message" => "Post don't found")));
            $response->setStatusCode(404, "Post don't found");
            return $response;
        }
        $response = new JsonResponse();
        $validator = Validator::make($request->all(), [
            'title' => 'unique:posts',
            'image' => 'max:2048|image',
        ]);
        if(!$validator->fails()){
            $post->fill($request->all());
            $file = $request->file('image');
            if($file != null){
                $file->storeAs('/post_images', $file->getClientOriginalName());
                $post->image = $request->root() . '/api/post_images/' . $file->getClientOriginalName();
            }
            $post->save();
            $response->setJson( json_encode( array( 'status' => 'true', 'post' => $post ) ) );
            $response->setStatusCode(201, 'Successful creation');
            return $response;
        }
        else{
            $response->setJson( json_encode( array( 'status' => false, 'message' => $validator->errors()) ) );
            $response->setStatusCode(400, 'Editing error');
            return $response;
        }
    }

    /**
     * Update the specified resource in api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from api.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);
        $response = new JsonResponse();
        if($post == null || $post == ""){
            $response->setJson(json_encode(array("message" => "Post don't found")));
            $response->setStatusCode(404, "Post don't found");
            return $response;
        }
        $post->delete();
        $response->setJson(json_encode(array("status" => true)));
        $response->setStatusCode(201, "Successful delete");
        return $response;
    }
}
