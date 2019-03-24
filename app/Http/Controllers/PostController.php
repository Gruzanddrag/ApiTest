<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public static $userToken = "03739B69410B83A49EC9629A64A53B4F";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = new Response();
        $req = $request->all();
//        foreach ($json as $v){
//            $mas = array_add($mas, 'AW', );
//        }
        $json = json_encode(array_shift($req));
        $response->setContent($json);
        return $response;
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
        $allFiles = $request->file();
        $file = array_shift($allFiles);
        $imageURL = null;
        $token = $request->bearerToken();
        if($token != PostController::$userToken && $token != null){
            $response->setJson( json_encode( array( 'message' => 'Unauthorized') ) );
            $response->setStatusCode(401, 'Unauthorized');
            return $response;
        }
        $json = json_decode(array_shift($req));
        $title = $json->{'title'} ?? $errors = array_add($errors, 'title', 'title is empty');
        $anons = $json->{'anons'} ?? $errors = array_add($errors, 'anons', 'anons is empty');
        $text = $json->{'text'} ?? $errors = array_add($errors, 'text', 'text is empty');
        $colZTags = isset($json->{'tags'}) ? substr_count($json->{'tags'}, ",") : 0;
        $colSpaceTags = isset($json->{'tags'}) ? substr_count($json->{'tags'}, " ") : 0;
        if(!isset($json->{'tags'})){
            $errors = array_add($errors, 'tags' , 'incorrect tags format');
        }
        else{
            $tags = $json->{'tags'};
        }
        if(isset($file)){
            $file->storeAs('/post_images', $file->getClientOriginalName());
            $imageURL = 'http://myapitest/api/post_images/' . $file->getClientOriginalName();
        }
        else{
            $errors = array_add($errors, 'image', 'no image');
        }
        $dbTitle = DB::table('posts')->where('title', '=', $title)->value('title');
        if(isset($dbTitle)){
            $errors = array_add($errors, 'title', 'already exists');
        }
        if(count($errors) === 0){
            $postId = DB::table('posts')->insertGetId(['title' => $title, 'anons' => $anons, 'text' => $text, 'tags' => $tags, 'image' => $imageURL]);
            $response->setJson( json_encode( array( 'status' => 'true', 'post_id' => $postId, 'image' => $imageURL ) ) );
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
    public function edit(Article $article)
    {
        //
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
