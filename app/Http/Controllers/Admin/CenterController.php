<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function index(Request $request){
            return view('admin.center.index',['centers'=>Center::latest()->get()]);
    }

    public function addCollectionCenter(Request $request){
        $center = new Center();
        $center->name = $request->name;
        $center->addresss = $request->address;
        $center->fat_rate = $request->fat_rate??0;
        $center->cc = $request->cc??0;
        $center->tc = $request->tc??0;
        $center->snf_rate = $request->snf_rate??0;
        $center->show_cc = $request->show_cc??0;
        $center->show_ts = $request->show_ts??0;
        $center->use_protsahan = $request->use_protsahan??0;
        $center->use_transport = $request->use_transport??0;
        $center->protsahan = $request->protsahan??0;
        $center->bonus = $request->bonus??0;
        $center->save();
        return view('admin.center.single')->with(compact('center'));
    }

    public function updateCollectionCenter(Request $request){
        // dd($request->all());
        $center = Center::where('id',$request->id)->first();
        $center->name = $request->name;
        $center->addresss = $request->address;
        $center->fat_rate = $request->fat_rate??0;
        $center->snf_rate = $request->snf_rate??0;
        $center->cc = $request->cc??0;
        $center->tc = $request->tc??0;
        $center->show_cc = $request->show_cc??0;
        $center->show_ts = $request->show_ts??0;
        $center->use_protsahan = $request->use_protsahan??0;
        $center->use_transport = $request->use_transport??0;
        $center->protsahan = $request->protsahan??0;
        $center->bonus = $request->bonus??0;
        $center->save();
        return view('admin.center.single')->with(compact('center'));
    }

    public function listCenter(){
        $centers = Center::latest()->get();
        return response()->json($centers);
        // return view('admin.center.list',compact('centers'));
    }

    public function deleteCenter(Request $request){
        $center = Center::where('id',$request->id)->first();
        $center->delete();
        return response('ok');
    }

}
