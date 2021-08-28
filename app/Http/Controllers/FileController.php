<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function get(Request $request)
    {
        $kegiatan_id = $request->input('kegiatan_id');
        $path = $request->input('path');

        $result = app('db')->select('select * from file where kegiatan_id = ? and path = ?', [$kegiatan_id, $path]);
        return response()->json($result);
    }

    public function insert(Request $request)
    {
        $judul = $request->input('judul');
        $path = $request->input('path');
        $folder = $request->input('folder');
        $kegiatan_id = $request->input('kegiatan_id');
        $user_id = $request->input('user_id');
        
        if ($folder == '0') {
            $file = $request->file('file');
            $ekstensi = $request->input('ekstensi');
            $nama_split = explode('.', $file->getClientOriginalName());
            $nama = str_replace('.' . end($nama_split), '', $file->getClientOriginalName());
            $lokasi = $file->move('storage', $nama . '_' . $judul . '_' . $kegiatan_id . '_' . $user_id . '_' . date('YmdHis') . '.' . $ekstensi);
        }

        if ($folder == '1') {
            $lokasi = '';
        }
        
        $result = app('db')->insert('insert into file (judul, lokasi, path, folder, kegiatan_id, user_id) values (?, ?, ?, ?, ?, ?)', [$judul, $lokasi, $path, $folder, $kegiatan_id, $user_id]);
        return response()->json(['pesan' => 'Data berhasil ditambahkan']);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $judul_baru = $request->input('judul');
        
        $file = app('db')->select('select * from file where id = ?', [$id])[0];
        $folder = $file->folder;

        if ($folder == '1') {
            $kegiatan_id = $file->kegiatan_id;

            $judul_lama = $file->judul;
            $path = $file->path;
            $full_path_lama = $path . '/' . $judul_lama;

            $file_inside_folder = app('db')->select('select * from file where path = ? and kegiatan_id = ?', [$full_path_lama, $kegiatan_id]);

            $full_path_baru = $path . '/' . $judul_baru;

            foreach ($file_inside_folder as $f) {
                app('db')->update('update file set path = ? where id = ?', [$full_path_baru, $f->id]);;
            }

        }

        $result = app('db')->update('update file set judul = ? where id = ?', [$judul_baru, $id]);
        return response()->json(['pesan' => 'Data berhasil diubah']);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $file = app('db')->select('select * from file where id = ?', [$id])[0];
        $folder = $file->folder;

        if ($folder == '0') {
            $lokasi = $file->lokasi;
            unlink($lokasi);

            $result = app('db')->delete('delete from file where id = ?', [$id]);
            return response()->json(['pesan' => 'Data berhasil dihapus']);
        }

        if ($folder == '1') {
            $kegiatan_id = $file->kegiatan_id;

            $judul = $file->judul;
            $path = $file->path;
            $full_path = $path . '/' . $judul;

            $file_inside_folder = app('db')->select('select * from file where path = ? and kegiatan_id = ?', [$full_path, $kegiatan_id]);

            if (count($file_inside_folder) == 0) {
                $result = app('db')->delete('delete from file where id = ?', [$id]);
                return response()->json(['pesan' => 'Data berhasil dihapus']);
            } else {
                return response()->json(['pesan' => 'Data tidak kosong']);
            }
        }

    }

}
