<?php

namespace App\Http\Controllers;

use App\Article;
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
        $req = $request->all();
        $allFiles = $request->file();
        $file = array_shift($allFiles);
        $file->storeAs('/', $file->getClientOriginalName());
        $json = json_decode(array_shift($req));
        $errors = array();
        if(!isset($json->{'title'}) || $json->{'title'} === "") {
            $errors = array_add($errors, 'title', 'title is empty');
        }
        else{
            $title = $json->{'title'};
        }
        if(!isset($json->{'anons'}) || $json->{'anons'} === ""){
            $errors = array_add($errors, 'anons', 'anons is empty');
        }
        else{
            $anons = $json->{'anons'};
        }
        if(!isset($json->{'text'}) || $json->{'text'} === ""){
            $errors = array_add($errors, 'text' , 'text is empty');
        }
        else{
            $text = $json->{'text'};
        }
        $colZTags = substr_count($json->{'tags'}, ",");
        $colSpaceTags = substr_count($json->{'tags'}, " ");
        if(isset($json->{'tags'}) && ($colSpaceTags > 0 && $colZTags == 0)){
            $errors = array_add($errors, 'tags' , 'incorrect tags format');
        }
        else{
            $tags = $json->{'tags'};
        }
//        if (!isset($image)) {
//            $errors = array_add($errors, 'image', 'no image');
//        }
        //TODO: Make unique test from DB
        $image = $request->input('image');
        $response = new JsonResponse();
        if(count($errors) === 0){
            $response->setJson( json_encode( array( 'title' => $title, 'anons' => $anons, 'text' => $text, 'tags' => $tags) ) );
//              $response->setJson( json_encode( array( 'title' => $title) ) );
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
     * Store a newly created resource in post_images.
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
     * Update the specified resource in post_images.
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
     * Remove the specified resource from post_images.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
