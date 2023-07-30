<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use App\Models\MainModel;
use App\Models\InteractModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FileSystem;


class MainController extends Controller
{
    function __construct() {

        $this->MainModel = new MainModel;
        $this->InteractModel = new InteractModel;
    }

    function home() {
        $snaps = MainModel::all();
        $interacts = InteractModel::all();

        $userId = null;
        
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $userName = Auth::user()->name;
        }

        return view('home', compact('snaps', 'interacts', 'userId', 'userName'));
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->get('query');
        $data = json_decode(file_get_contents(resource_path('adjctvs.json')), true);
        $results = [];

        foreach ($data as $adjective) {
            if (stristr($adjective, $query)) {
                $results[] = $adjective;
            }
        }

        return response()->json($results);
    }

    function saveTask(Request $request) {

        if ($request->hasFile('imagefile')) {
            $imagePath = $request->file('imagefile')->path();
            $imageData = base64_encode(FileSystem::get($imagePath));
            $contentType = $request->file('imagefile')->getMimeType();
        }

        $data = [
            'task_name' => ucfirst($request->input('taskname')),
            'user_name' => $request->input('username'),
            'image_data' => $imageData ?? null,
            'image_content_type' => $contentType ?? null,
        ];

        $this->MainModel->saveTask($data);
        Session::flash('success', 'Snap Posted!');

        return back();
    }

    
    function deleteTask($id) {
        $this->MainModel->deleteTask($id);
        return back();
    }

    function updateTask($id) {
        $task = $this->MainModel->getTaskById($id);

        return view('update-task', compact('task'));
    }

    function saveUpdatedTask(Request $request){
        $data = [
            'task_name' => $request->updatetask
        ];

        $this->MainModel->updateTask($data, $request->id);
        return redirect()->route('home');
    }

    // function storyInteract(Request $request){
    //     $data = [ 
    //         'user_id' => $request->userid ,
    //         'story_id' => $request->storyid
    //     ];

    //     // var_dump($data);
    //     $this->InteractModel->saveStoryInteract($data);

    //     return back();
    // }

    function storyInteract(Request $request) {
        $data = [
            'user_id' => $request->userid,
            'story_id' => $request->storyid
        ];
    
        // Save the interaction
        $this->InteractModel->saveStoryInteract($data);
    
        // Return a response indicating success
        return response()->json(['status' => 'success']);
    }
}
