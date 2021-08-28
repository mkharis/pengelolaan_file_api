<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get()
    {
        $result = app('db')->select('select nama, level from user');
        return response()->json($result);
    }

    public function insert(Request $request)
    {
        $nama = $request->input('nama');
        $password = $request->input('password');
        $level = $request-> input('level');

        $result = app('db')->insert('insert into user (nama, password, level) values (?, ?, ?)', [$nama, app('hash')->make($password), $level]);
        return response()->json(['pesan' => 'User berhasil ditambahkan']);
    }

    public function password(Request $request)
    {
        $id = $request->input('id');
        $password = $request->input('password');

        $result = app('db')->update('update user set password = ? where id = ?', [app('hash')->make($password), $id]);
        return response()->json(['pesan' => 'Password berhasil diubah']);
    }

}
