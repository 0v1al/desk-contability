<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.user');
    }

    public function upload_file(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $file->getClientMimeType();
            $fileName = $file->getClientOriginalName();

            $fileAlready = UserFile::where('name', $fileName)
                ->where('user_id', auth()->user()->id)
                ->first();

            if ($fileAlready) {
                return redirect()->back()
                    ->with('error', 'Un fișier cu același nume există deja');
            }

            auth()->user()->files()->create([
                'name' => $fileName,
                'type' => $fileType
            ]);

            $file->storeAs('files', $fileName, 'public');

            return redirect()->back()
                ->with('message', 'Fișierul a fost încărcat cu success');
        } else {
            return redirect()->back()
                ->with('error', 'Trebuie să adăugați un fișier din computer-ul dumneavoastră');
        }
    }

    public function user_files()
    {
        $userFiles = auth()->user()->files()
            ->where('uploaded_by_admin', false)->get();

        return view('user.user', ['userFiles' => $userFiles]);
    }

    public function admin_user_files()
    {
        $userFiles = auth()->user()->files()
            ->where('uploaded_by_admin', true)->get();

        return response(['data' => $userFiles, 'success' => true]);
    }


    public function received_files()
    {
        $userId = auth()->user()->id;

        $receivedFiles = UserFile::where('uploaded_by_admin', true)
            ->where('user_id', $userId)
            ->get();

        return view('user.receivedfiles', ['receivedFiles' => $receivedFiles]);
    }

    public function delete_user_file(Request $request)
    {
        $userFileId = $request->input('userFileId');
        $userId = auth()->user()->id;

        $userFile = UserFile::where('user_id', $userId)
            ->where('id', $userFileId)
            ->first();

        if ($userFile) {
            $userFileName = $userFile->name;

            Storage::delete('public/files/' . $userFileName);
            $userFile->delete();

            $request->session()->flash('message', 'Fișierul a fost șters cu success');

            return response()->json(['success' => true]);
        } else {
            $request->session()->flash('error', 'Eroare la ștergerea fișierului');

            return response()->json(['success' => false]);
        }
    }

    public function view_user_file(Request $request)
    {
        $userId = auth()->user()->id;
        $userFileId = $request->input('userFileId');

        $userFile = UserFile::where('user_id', $userId)
            ->where('id', $userFileId)
            ->first();

        if ($userFile) {
            $userFileName = $userFile->name;
            $userFileType = $userFile->type;
            $fileUrl = Storage::path('/public/files/' . $userFileName);

            $headers = array(
                'Content-Type' => $userFileType
            );

            return response()->file($fileUrl, $headers);
        }
    }

    public function download_user_file(Request $request)
    {
        $userId = auth()->user()->id;
        $userFileId = $request->input('userFileId');

        $userFile = UserFile::where('user_id', $userId)
            ->where('id', $userFileId)
            ->first();

        if ($userFile) {
            $userFileName = $userFile->name;
            $userFileType = $userFile->type;
            $fileUrl = Storage::path('/public/files/' . $userFileName);

            $headers = array(
                'Content-Type' => $userFileType
            );

            return response()->download($fileUrl, $userFileName, $headers);
        }
    }
}
