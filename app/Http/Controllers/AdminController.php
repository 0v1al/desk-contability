<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // $users = User::where('id', '!=', Auth::user()->id)->get();
        $users = User::all()->except(Auth::user()->id);

        return view('admin.users', ["users" => $users]);
    }

    public function user_files($id)
    {
        $user = User::where('id', $id)->first();
        $userFiles = $user->files;

        return view('admin.userfiles', [
            'user' => $user,
            'userFiles' => $userFiles
        ]);
    }

    public function delete_user_file(Request $request)
    {
        $userFileId = $request->input('userFileId');
        $userId = $request->input('userId');

        $user = User::where('id', $userId)->first();

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
            $request->session()->flash('message', 'Eroare la ștergerea fișierului');

            return response()->json(['success' => false]);
        }
    }

    public function view_user_file(Request $request)
    {
        $userId = $request->input('userId');
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
        $userId = $request->input('userId');
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

    //return the edit client page
    public function edit_user(Request $request)
    {
        $userId = $request->input('userId');
        $user = User::where('id', $userId)->first();

        return view('admin.edituser', ['user' => $user]);
    }

    public function update_user(Request $request)
    {
        $input = $request->only(['name', 'email']);
        //new client details
        $userName = $request->input('name');
        $userEmail = $request->input('email');
        //old client details
        $oldUserEmail = $request->input('oldUserEmail');
        $userId = $request->input('userId');

        $messages = [
            'required' => 'Câmpul email trebuie completat',
            'email' => 'Adresa de email nu este validă',
            'email.max' => 'Lungimea maximă pentru email a fost depășită',
            'name.max' => 'Lungimea maximă pentru nume a fost depășită',
            'name.unique' => 'Un cont cu același nume există deja'
        ];

        $rules = [
            'email' => 'required|max:255|email',
            'name' => 'required|max:255|unique'
        ];

        $validator = Validator::make($input, $rules, $messages);
        $validator->validate();

        $user = User::where('email', $oldUserEmail)
            ->where('id', $userId)
            ->first();

        $user->name = $userName;
        $user->email = $userEmail;
        $user->save();

        return redirect()->back()
            ->with('message', 'Clientul a fost actualizat cu success');
    }

    public function delete_user_account(Request $request)
    {
        $userId = $request->input('userId');
        $user = User::where('id', $userId)->first();

        if ($user) {
            $userFiles = $user->files;

            foreach ($userFiles as $userFile) {
                $userFileName = $userFile->name;

                Storage::delete('/public/files/' . $userFileName);
                $userFile->delete();
            }

            $user->delete();

            $userMessages = Message::where('from', $userId)
                ->where('to', $userId)->get();
            $userMessages->delete();

            $request->session()->flash('message', 'Contul a fost șters cu success');

            return response()->json(['success' => true]);
        } else {
            $request->session()->flash('error', 'Eroare la ștergerea contului');

            return response()->json(['success' => false]);
        }
    }

    public function index_upload_user_file(Request $request)
    {
        $users = User::where('is_admin', '!=', true)->get();

        return view('admin.userfileupload', ["users" => $users]);
    }

    public function upload_user_file(Request $request)
    {
        $userId = $request->input('user-id');

        if (!$userId) {
            return redirect()->back()
                ->with('error', 'Trebuie să selectați mai întâi un client');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $file->getClientMimeType();
            $fileName = $file->getClientOriginalName();

            $fileAlready = UserFile::where('name', $fileName)
                ->where('user_id', $userId)
                ->first();

            if ($fileAlready) {
                return redirect()->back()
                    ->with('error', 'Un fișier cu același nume există deja');
            }

            UserFile::create([
                'user_id' => $userId,
                'name' => $fileName,
                'type' => $fileType,
                'uploaded_by_admin' => true,
                'admin_name' => auth()->user()->name
            ]);

            $file->storeAs('files', $fileName, 'public');

            $userName = User::where('id', $userId)->first()->name;

            return redirect()->back()
                ->with('message', 'Fișierul a fost încărcat cu success. Acesta poate fi accesat de către clientul ' . $userName);
        } else {
            return redirect()->back()
                ->with('error', 'Trebuie să adăugați un fișier din computer-ul dumneavoastră');
        }
    }
}
