<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ViewProduk()
    {
        $produk = Produk::all(); //mengambil semua data di tabel produk
        return view('produk', ['produk' => $produk]); //menmpilkan view dari produk.blade.php dengan membawa variabel $produk
    }
    public function CreateProduk(Request $request)
    { // Inisialisasi variabel untuk nama file dan jalur penyimpanan
    $imageName = null;
    $filePath = null;

    // Cek apakah ada file gambar yang diunggah
    if ($request->hasFile('image')) {
        $imageFile = $request->file('image');
        $imageName = time() . '_' . $imageFile->getClientOriginalName();  // Nama file disertai timestamp
        $filePath = $imageFile->storeAs('public/images', $imageName);  // Menyimpan file di storage/app/public/images
    }

    // Buat produk baru dengan data yang diberikan dan nama file gambar (jika ada)
    produk::create([
        'nama_produk' => $request->nama_produk,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'jumlah_produk' => $request->jumlah_produk,
        'image' => $imageName,  // Simpan nama file gambar ke database
    ]);

        return redirect('/produk');
    }
    public function ViewAddProduk()
    {
        return view('addproduk'); //menampilkan view dari add.produk.blade.php
    }

    public function DeleteProduk($kode_produk)
    {
        Produk::where('kode_produk', $kode_produk) ->delete(); // find teh record by id


        return redirect('/produk')->with('success', 'Produk berhasil dihapus');
    }
    //fungsi untuk view edit produk
    public function ViewEditProduk($kode_produk)
    {
        $ubahproduk = Produk::where('kode_produk', $kode_produk)->first();

        return view('editproduk', compact('ubahproduk'));
    }
    //fungsi untuk mengubah data produk
    public function UpdateProduk(Request $request,$kode_produk)
    {
        Produk::where('kode_produk', $kode_produk)->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jumlah_produk' => $request->jumlah_produk,
        ]);
        return redirect('/produk');
    }
}
