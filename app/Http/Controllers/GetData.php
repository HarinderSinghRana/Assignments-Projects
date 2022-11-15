<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\post;
use DataTables;


class GetData extends Controller
{
    public function index()
    {
        $api_url = 'https://jsonplaceholder.typicode.com/posts';
        $response = Http::get($api_url);
        $data = json_decode($response->body());

       echo "<pre>";

       foreach($data as $post)
       {
        $post = (array)$post;
        post::updateOrCreate(
            ['id' =>$post['id']],
            [
                'id' => $post['id'],
                'userId' => $post['userId'],
                'title' => $post['title'],
                'body' => $post['body'],
            ]
        );

       }
       dd("data stored");
    }

    public function viewdata(Request $request)
    {
        if ($request->ajax()) {
            $data = post::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('showdata');
    }
}
