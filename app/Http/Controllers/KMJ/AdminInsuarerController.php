<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\Kmj\Insuarer;
use App\Models\Models\Kmj\Customer;
use Illuminate\Support\Facades\Hash;

class AdminInsuarerController extends Controller
{
    public function index(){
        $insuarers = Insuarer::all();
        $totalInsuarers = Insuarer::count();

        return view('kmj.insuarer.index', compact('totalInsuarers','insuarers'));
    }

    public function create(){
        return view('kmj.insuarer.create');
    }
    public function store(Request $request){

        
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|email|unique:insuarers,email',
            'password' => 'required|string|min:6',
            'company_code' => 'required|string|unique:insuarers,Company_code',
            'insuarer_code' => 'required|string|unique:insuarers,Insuarer_code',

        ]);

        Insuarer::create([
            'name' =>$request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'Company_code' => $request->company_code,
            'Insuarer_code' => $request->insuarer_code,
        ]);

        return redirect()->route('admin.insuarers.index')->with('success','Insuarer added succesfully');
    }
}
