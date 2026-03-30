<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /*
    =========================================
    LIST USER
    =========================================
    */

    public function index(Request $request)
    {

        $query = User::query();

        /*
        ================================
        SEARCH
        ================================
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search){

                $q->where('name','like',"%{$search}%")
                  ->orWhere('email','like',"%{$search}%");

            });
        }

        /*
        ================================
        SORT
        ================================
        */

        $sort = $request->get('sort','created_at');
        $direction = $request->get('direction','desc');

        $allowedSort = ['name','email','role','created_at'];

        if (!in_array($sort,$allowedSort)) {
            $sort = 'created_at';
        }

        $users = $query
            ->orderBy($sort,$direction)
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index',compact('users','sort','direction'));
    }


    /*
    =========================================
    CREATE
    =========================================
    */

    public function create()
    {
        return view('admin.pengguna.create');
    }


    /*
    =========================================
    STORE
    =========================================
    */

    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:pegawai,admin,kasubag'

        ]);

        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role

        ]);

        return redirect()
            ->route('admin.pengguna')
            ->with('success','User berhasil dibuat.');
    }


    /*
    =========================================
    EDIT
    =========================================
    */

    public function edit($id)
    {

        $user = User::findOrFail($id);

        return view('admin.pengguna.edit',compact('user'));
    }


    /*
    =========================================
    UPDATE
    =========================================
    */

    public function update(Request $request,$id)
    {

        $user = User::findOrFail($id);

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:pegawai,admin,kasubag'

        ]);

        $user->update([

            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role

        ]);

        if ($request->filled('password')) {

            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()
            ->route('admin.pengguna')
            ->with('success','User berhasil diperbarui.');
    }


    /*
    =========================================
    DELETE
    =========================================
    */

    public function destroy($id)
    {

        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success','User berhasil dihapus.');
    }

}