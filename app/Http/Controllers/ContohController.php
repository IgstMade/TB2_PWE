<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContohController extends Controller
{
    public function TampilContoh()
    {
        $data = [
            'totalProducts' => 310,
            'salesToday' => 100,
            'totalRevenue' => 'Rp 75,000,000',
            'registeredUsers' => 350
        ];
        return view('contoh', $data);
    }
}
