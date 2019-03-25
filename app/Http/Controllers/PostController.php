<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $allFiles = $request->file();
        $file = current($allFiles);
        $req = (object) $request->all();
        $json = json_decode(current($req)) ?? $req;
        $tags = $json->{'tags'};
        trim($tags);
        $f = false;
        $tags = strpos($tags, ' ') ? $f = true : explode(',', $tags);
        if($f){
            return "SOSATB";
        }
        $tags = explode(',', $tags);
        Log::info($tags);
        $response = new Response();
        $response->setContent('awddwa');
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
        $imageURL = null;
        $json = json_decode(array_shift($req));
        $title = $json->{'title'} ?? $errors = array_add($errors, 'title', 'title is empty');
        $anons = $json->{'anons'} ?? $errors = array_add($errors, 'anons', 'anons is empty');
        $text = $json->{'text'} ?? $errors = array_add($errors, 'text', 'text is empty');
        $tags = $json->{'tags'};
        trim($tags);
        $tags = strpos($tags, ' ') ? $errors = array_add($errors, 'tags' , 'incorrect tags format') : explode(',', $tags);
        $file = current($request->file());
        if(isset($file)){
            $file->storeAs('/post_images', $file->getClientOriginalName());
            $imageURL = $request->root() . '/api/post_images/' . $file->getClientOriginalName();
        }
        else{
            $errors = array_add($errors, 'image', 'no image');
        }
        //Проверка на уникальность
        $checkUnique = DB::table('posts')->where('title', '=', $title)->value('title') == null ? : $errors = array_add($errors, 'title', 'already exists');Log::info($checkUnique);
        if(count($errors) === 0){
            $postId = DB::table('posts')->insertGetId(['title' => $title, 'anons' => $anons, 'text' => $text, 'tags' => json_encode($tags), 'image' => $imageURL]);
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
