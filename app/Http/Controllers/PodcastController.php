<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\AudioFile;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index()
    {
        return Podcast::all();
    }

    public function getAll()
    {
        $podcasts = Podcast::with('audioFiles')->get();

        return response()->json([
            'podcasts' => $podcasts,
        ]);
    }

    public function get(Request $request)
    {
        $podcast = new Podcast();
        if ($request->search != '') {
            $podcast = $podcast->where('author', 'like', '%' . $request->search . '%')->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $podcast = $podcast->orderByDesc('id')->paginate(25);
        return response()->json($podcast);
    }

    public function show($id)
    {
        return Podcast::find($id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'author' => 'required',
            // 'audio_file' => 'required|file|mimes:mp3,wav', // Adjust the file types as needed
        ]);

        // // Handle file upload and store the file
        // if ($request->hasFile('audio_file')) {
        //     $audioFile = $request->file('audio_file');
        //     $audioFileName = time() . $audioFile->getClientOriginalName();
        //     $audioFile->move('audio', $audioFileName);
        // } else {
        //     // Handle the case where no file was provided or validation failed
        //     return response()->json(['message' => 'Audio file is required.'], 400);
        // }
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageFileName = time() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move('images', $imageFileName);
            // Create the Podcast model with the validated data and the stored file name
            $podcast = Podcast::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'author' => $validatedData['author'],
                'image' => $imageFileName,
            ]);

            return response()->json(['message' => $imageFileName]);

        } else {
                // Handle the case where no file was provided or validation failed
            return response()->json(['message' => 'Image file is required.'], 400);
        }



    }


    public function update(Request $request)
{
    $podcast = Podcast::findOrFail($request->id);
    $podcast->title = $request->title;
    $podcast->description = $request->description;
    $podcast->author = $request->author;

    // Check if an audio file was uploaded and update it if not null
    // if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
    //     $audioFileName = time() . $request->file('audio_file')->getClientOriginalName();
    //     $request->file('audio_file')->move('audio', $audioFileName);
    //     $podcast->audio_file = $audioFileName;
    // }

    // Check if an image file was uploaded and update it if not null
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $imageFileName = time() . $request->file('image')->getClientOriginalName();
        $request->file('image')->move('images', $imageFileName);
        $podcast->image = $imageFileName;
    }

    $podcast->save();

    return $podcast;
}

    public function destroy(Request $request)
    {
        $podcast = Podcast::findOrFail($request->id);
        $podcast->delete();

        return 204;
    }

    public function getAudioFiles(Request $request, $id)
    {
        // Load the audio files related to the podcast
        $audioFiles = AudioFile::where('podcast_id', $id)->get();

        return response()->json($audioFiles);
    }

    public function addAudioFile(Request $request)
    {
        // Validate the incoming request, ensuring it has an audio_file
        $request->validate([
            'audio_file' => 'required|mimes:mp3,wav,ogg',
        ]);

        // Find the podcast by its ID
        $podcast = Podcast::findOrFail($request->podcast_id);

        // Store the uploaded audio file and associate it with the podcast
        if ($request->hasFile('audio_file')) {
            $audioFile = $request->file('audio_file');
            $audioFileName = time() . '_' . $audioFile->getClientOriginalName();

            // Move the uploaded file to a storage location (e.g., public/audio)
            $audioFile->move('audio', $audioFileName);

            // Create a new AudioFile record in the database
            $newAudioFile = new AudioFile();
            $newAudioFile->audio_file_name = $audioFileName;

            // Save the AudioFile record
            $podcast->audioFiles()->save($newAudioFile);

            // Return a success response
            return response()->json(['message' => 'Audio file added successfully']);
        }

        // Return an error response if the file upload failed
        return response()->json(['message' => 'Failed to upload audio file'], 400);
    }
}
