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
    public function lol(){
        return view('addPost');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    public function validateTags($tags){
        Log::info(json_encode($tags));
        if(!is_array($tags)){
            $tags = trim($tags);
            if(isset($tags)){
                if (strpos($tags, ' ') || substr($tags, -1) == ','){
                    return false;
                }
                else{
                    $tags = explode(',', $tags);
                }
            }
        }
        return $tags;
    }

    /**
     * @param $file
     * @return array|bool
     */
    public function validateImage(uploadedFile $file){
        $errors = array();
        if($file->extension() != 'png' && $file->extension() != 'jpeg'){
            $errors = array_add($errors, 'image', 'invalid file extension');
        }
        else if($file->getSize() / 1024 / 1024 > 2){
            $errors = array_add($errors, 'image', 'file size is greater than 2MB');
        }
        else{
            $file->storeAs('/post_images', $file->getClientOriginalName());
            return true;
        }
        return $errors;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = new JsonResponse();
        $errors = array();
        $req = $request->all();
        $json = json_decode(array_shift($req), true);
        $file = current($request->file());
        if($file != null || $file != ''){
            $validFile = $this->validateImage($file);
        }
        else{
            $errors = array_add($errors, 'image', 'no image');
        }
        $imageURL = $validFile === true ? $request->root() . '/api/post_images/' . $file->getClientOriginalName() : $errors = $validFile;
        $title = $json['title'] ?? $errors = array_add($errors, 'title', 'title is empty');
        $anons = $json['anons'] ?? $errors = array_add($errors, 'anons', 'anons is empty');
        $text = $json['text'] ?? $errors = array_add($errors, 'text', 'text is empty');
        $tags = isset($json['tags']) ? $json['tags'] : null;
        $tags = $this->validateTags($tags);
        //Log::info($tags);
        $errors = $tags != false ? $errors : $errors = array_add($errors, 'tags' , 'incorrect tags format');
        //Проверка на уникальность
        $checkUnique = Post::where('title', $title)->first() == null ? : $errors = array_add($errors, 'title', 'already exists');Log::info($checkUnique);
        if(count($errors) === 0){
            $post = new Post;
            $post->fill($json);
            $post->image = $imageURL;
            $post->save();
            $response->setJson( json_encode( array( 'status' => 'true', 'post_id' => $post->id, 'image' => $imageURL ) ) );
            $response->setStatusCode(201, 'Successful creation');
            return $response;
        }
        else{
            $response->setJson( json_encode( array( 'status' => false, 'message' => $errors) ) );
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $idPost)
    {
        $req = $request->all();
        $json = json_decode(array_shift($req), true);
        $post = Post::find($idPost);
        if($post == null || $post == ""){
            $response = new JsonResponse();
            $response->setJson(json_encode(array("message" => "Post don't found")));
            $response->setStatusCode(404, "Post don't found");
            return $response;
        }
        $response = new JsonResponse();
        $errors = array();
        $file = current($request->file());
        $validFile = false;
        if($file != null && $file != ''){
            $validFile = $this->validateImage($file);
        }
        $imageURL = $validFile != true ?  : $request->root() . '/api/post_images/' . $file->getClientOriginalName();
        $post->image = $imageURL != false ? $imageURL : $post->image;
        $tags = isset($json['tags']) ? $json['tags'] : null;
        $tags = $this->validateTags($tags);
        $title = $json['title'];
        $errors = $tags != false ? $errors : $errors = array_add($errors, 'tags' , 'incorrect tags format');
        $checkUnique = Post::where('title', $title)->first() == null ? : $errors = array_add($errors, 'title', 'already exists');
        if(count($errors) === 0){
            $post->fill($json);
            $post->save();
            $response->setJson( json_encode( array( 'status' => 'true', 'post' => $post ) ) );
            $response->setStatusCode(201, 'Successful creation');
            return $response;
        }
        else{
            $response->setJson( json_encode( array( 'status' => false, 'message' => $errors) ) );
            $response->setStatusCode(400, 'Editing error');
            return $response;
        }
        return json_encode($post->first());
    }

    /**
     * Update the specified resource in api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from api.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
