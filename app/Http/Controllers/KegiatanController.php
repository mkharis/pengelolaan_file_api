<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function get()
    {
        $result = app('db')->select('select * from kegiatan');
        return response()->json($result);
    }

    public function getJoinFungsi()
    {
        $result = app('db')->select('select kegiatan.id, kegiatan.kegiatan, kegiatan.tahun, kegiatan.fungsi_id, fungsi.fungsi from kegiatan join fungsi on kegiatan.fungsi_id = fungsi.id');
        return response()->json($result);
    }

    public function getByFungsi($fungsi_id)
    {
        $result = app('db')->select('select * from kegiatan where fungsi_id = ?', [$fungsi_id]);
        return response()->json($result);
    }

    public function insert(Request $request)
    {
        $kegiatan = $request->input('kegiatan');
        $tahun = $request->input('tahun');
        $fungsi_id = $request->input('fungsi_id');

        $result = app('db')->insert('insert into kegiatan (kegiatan, tahun, fungsi_id) values (?, ?, ?)', [$kegiatan, $tahun, $fungsi_id]);
        return response()->json(['pesan' => 'Data berhasil ditambahkan']);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $kegiatan = $request->input('kegiatan');
        $tahun = $request->input('tahun');
        $fungsi_id = $request->input('fungsi_id');

        $result = app('db')->update('update kegiatan set kegiatan = ?, tahun = ?, fungsi_id = ? where id = ?', [$kegiatan, $tahun, $fungsi_id, $id]);
        return response()->json(['pesan' => 'Data berhasil diubah']);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $result = app('db')->delete('delete from kegiatan where id = ?', [$id]);
        return response()->json(['pesan' => 'Data berhasil dihapus']);
    }

}
