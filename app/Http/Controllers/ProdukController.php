<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ViewProduk()
    {
        $isAdmin = Auth::user()->role == 'admin';

        $produk = $isAdmin ? Produk::all() : Produk::where('user_id', Auth::user()->id)->get();

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
            'user_id' => Auth::user()->id
        ]);

        return redirect(Auth::user()->role.'/produk');
    }
    public function ViewAddProduk()
    {
        return view('addproduk'); //menampilkan view dari add.produk.blade.php
    }

    public function DeleteProduk($kode_produk)
    {
        Produk::where('kode_produk', $kode_produk) ->delete(); // find teh record by id


        return redirect(Auth::user()->role.'/produk')->with('success', 'Produk berhasil dihapus');
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
        return redirect(Auth::user()->role.'/produk');
    }

    public function ViewLaporan()
    {
        //mengambil semua data produk
        $products = Produk::all();
        return view('laporan', ['products'=> $products]);
    }

    public function print()
    {
        //mengambil semua data produk
        $products = Produk::all();

        //load view
        $pdf = Pdf::loadView('report', compact('products'));

        //menampilkan pdf
        return $pdf->stream('laporan-produk.pdf');
    }
}
