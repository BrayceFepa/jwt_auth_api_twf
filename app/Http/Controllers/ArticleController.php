<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function listArticle(){
        $articles = Article::all();
        return response()->json($articles, 200);
    }


    public function createArticle(Request $request){
        $inputs = $request->all();
        $requireParams = ['title', 'content'];
        foreach($requireParams as $param){
            if(!array_key_exists($param, $inputs)){
                return response()->json([
                    'message' => $param . ' field is required'    
                ], 401);
            }

            if(strlen($inputs[$param] == 0)){
                return response()->json(['message'=> $param . ' field is empty']);
            }
        }

        $article = Article::create($inputs);
        return response()->json([
        'status' => 'success',
        'message' => 'Article created successfully ',
        'article' => $article   
        ], 201);

    }

    public function updateArticle(Request $request){
        $inputs = $request->all();
        $requireParams = ['id', 'title', 'content'];

        foreach($requireParams as $param){
            if(!array_key_exists($param, $inputs)){
                return response()->json([
                    'status' => 'error',
                    'message' => $param . ' field is required'    
                ]);
            }

            if(strlen($inputs[$param]) == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => $param . ' field is empty !'    
                ]);
            }

        }

        $article = Article::find($inputs['id']);
        if($article){
            $article->update($inputs);
            return response()->json([
                'status' => 'success',
                'message' => 'article updated successfully !',
                'updatedArticle' => $article    
            ], 201);
        }else{
            return response()->json([
            'status' => 'error',
            'message' => 'Article not found !'    
            ], 404);
        }

    }

    public function deleteArticle($id){
        $article = Article::find($id);
        if($article){
            $article->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'article deleted successfully !'    
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'article not found !'    
            ]);
        }
    }


}
