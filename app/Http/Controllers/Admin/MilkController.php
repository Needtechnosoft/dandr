<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\Milkdata;
use App\Models\Product;
use App\Models\Snffat;
use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class MilkController extends Controller
{
    public function index()
    {
        return view('admin.milk.index');
    }

    public function saveMilkData(Request $request, $type)
    {
        // dd($request->all());
        $extracenters = explode(",", env('extracenter', ''));

        $actiontype = 0;
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('farmers.no', $request->user_id)->where('farmers.center_id', $request->center_id)->select('users.id', 'farmers.no', 'users.name', 'farmers.center_id')->first();
        // $user=User::where('no',$request->user_id)->first();
        // dd($user,$request);
        if ($user == null) {
            return response("Farmer Not Found", 400);
        } else {
            if ($user->no == null) {
                return response("Farmer Not Found", 500);
            }
        }

        $milkData = Milkdata::where('user_id', $user->id)->where('date', $date)->first();
        if ($milkData == null) {
            $milkData = new Milkdata();
            $milkData->date = $date;
            $milkData->user_id = $user->id;
            $milkData->center_id = $request->center_id;
            $actiontype = 1;
        }

        //request->type 1=save/replace type=2 add
        $product = Item::where('id', env('milk_id'))->first();
        $oldmilk = 0;
        if ($request->session == 0) {
            if ($type == 0) {
                $oldmilk = $milkData->m_amount;
                $milkData->m_amount = $request->milk_amount;
            } else {
                $milkData->m_amount += $request->milk_amount;
            }
        } else {
            if ($type == 0) {
                $oldmilk = $milkData->e_amount;
                $milkData->e_amount = $request->milk_amount;
            } else {
                $milkData->e_amount += $request->milk_amount;
            }
        }

        if ($product != null && !(in_array($request->center_id, $extracenters))) {
            if (env('multi_stock')) {

                $centerStock = $product->stock($request->center_id);
                $new = false;
                if ($centerStock == null) {
                    $centerStock = new CenterStock();
                    $centerStock->item_id = $product->id;
                    $centerStock->center_id = $request->center_id;
                    $centerStock->amount = 0;
                    $centerStock->save();
                    $new = true;
                }
            }
            if ($type == 0) {
                $product->stock -= $oldmilk;
                if (!$new && env('multi_stock')) {
                    $centerStock->amount -= $oldmilk;
                }
            }
            $product->stock += $request->milk_amount;
            if (env('multi_stock')) {
                $centerStock->amount += $request->milk_amount;
                $centerStock->save();
            }
            $product->save();
        }

        $milkData->save();
        $milkData->no = $user->no;
        $milkData->name = $user->name;
        if ($actiontype == 1) {
            return view('admin.milk.single', ['d' => $milkData]);
        } else {
            return response()->json($milkData->toArray());
        }
    }

    public function milkDataLoad(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $milkData = DB::table('milkdatas')
            ->join('farmers', 'farmers.user_id', '=', 'milkdatas.user_id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->where(['date' => $date, 'milkdatas.center_id' => $request->center_id])
            ->select('milkdatas.*', 'farmers.no', 'users.name')
            ->orderBy('milkdatas.id')
            ->get();
        return view('admin.milk.dataload', ['milkdatas' => $milkData]);
    }

    public function loadFarmerData(Request $request)
    {
        $farmers = User::join('farmers', 'farmers.user_id', '=', 'users.id')->where('farmers.center_id', $request->center)->where('users.role', 1)->select('users.*', 'farmers.center_id')->orderBy('users.no')->get();
        return view('admin.farmer.minlist', compact('farmers'));
    }

    public function update(Request $request)
    {
        $milkdata = Milkdata::find($request->id);
        $oldAmount = $milkdata->e_amount + $milkdata->m_amount;
        $milkdata->e_amount = $request->evening;
        $milkdata->m_amount = $request->morning;
        $newAmount = $milkdata->e_amount + $milkdata->m_amount;
        $amount = $newAmount - $oldAmount;
        $milkdata->save();

        $extracenters = explode(",", env('extracenter', ''));
        if (!in_array($milkdata->center_id, $extracenters)) {
            if ($oldAmount != $newAmount) {
                $milk_id = env('milk_id');

                if ($milk_id != null) {
                    $maincenter = Center::where('id', env('maincenter', null))->first();

                    $chalan = DB::selectOne("select 
                            si.id ,
                            si.amount 
                            from stock_outs s 
                            join stock_out_items si on si.stock_out_id=s.id
                            where s.date={$milkdata->date} and s.from_center_id = {$milkdata->center_id} and s.center_id={$maincenter->id} and si.item_id={$milk_id}");

                    if ($chalan != null) {
                        if ($amount > 0) {
                            maintainStock($milk_id, $amount, $maincenter->id, 'in');
                        } else {
                            $amount = -1 * $amount;
                            maintainStock($milk_id, $amount, $maincenter->id, 'out');
                        }
                        $milktotal = DB::selectOne("select sum(m_amount+e_amount) as amount from milkdatas where center_id={$milkdata->center_id} and date={$milkdata->date}")->amount ?? 0;
                        $si = StockOutItem::where('id', $chalan->id)->first();
                        $si->amount = $milktotal;
                        $si->save();
                    } else {

                        if ($amount > 0) {
                            maintainStock($milk_id, $amount, $milkdata->center_id, 'in');
                        } else {
                            $amount = -1 * $amount;
                            maintainStock($milk_id, $amount, $milkdata->center_id, 'out');
                        }
                    }
                }
            }
        }


        return response()->json([$amount, $oldAmount, $newAmount]);
    }
    public function delete(Request $request)
    {
        $milkdata = Milkdata::find($request->id);
        $milk_id = env('milk_id');
        if ($milk_id != null) {
            $amount = $milkdata->e_amount + $milkdata->m_amount;
            maintainStock($milk_id, $amount, $milkdata->center_id, 'out');
        }
        $milkdata->delete();
        return response('ok', 200);
    }


    public function chalan(Request $request)
    {
        if ($request->getMethod() == "POST") {
            try {
                $extracenters = explode(",", env('extracenter', ''));
                // dd($extracenters);
                $date = str_replace('-', '', $request->date);
                $maincenter = Center::where('id', env('maincenter', null))->first();
                $milk_id = env('milk_id', null);
                if ($maincenter == null) {
                    throw new \Exception('Please Set Main Center');
                }
                if ($milk_id == null) {
                    throw new \Exception('Please Set Milk Item');
                }
                if (count($extracenters) > 0) {

                    $centers = Center::where('id', '<>', $maincenter->id)->whereNotIn('id', $extracenters)->get();
                } else {
                    $centers = Center::where('id', '<>', $maincenter->id)->get();
                }

                foreach ($centers as $key => $center) {

                    $center->milktotal = DB::selectOne("select sum(m_amount+e_amount) as amount from milkdatas where center_id={$center->id} and date={$date}")->amount ?? 0;

                    $center->chalans = DB::select("select 
                    s.id,
                    si.id as stock_out_item_id,
                    si.amount 
                    from stock_outs s 
                    join stock_out_items si on si.stock_out_id=s.id
                    where s.date={$date} and s.from_center_id = {$center->id} and s.center_id={$maincenter->id} and si.item_id={$milk_id}");
                }
                // dd($centers);
                //code...
            } catch (\Throwable $th) {
                return response($th->getMessage());
            }



            // return response()->json($centers);
            return view('admin.milk.chalandata', compact('maincenter', 'centers', 'date'));
        } else {
            return view('admin.milk.chalan');
        }
    }

    public function chalanSave(Request $request)
    {
        $date = $request->date;
        $maincenter = Center::where('id', env('maincenter', null))->first();
        $milk_id = env('milk_id', null);
        if ($maincenter == null) {
            throw new \Exception('Please Set Main Center');
        }
        if ($milk_id == null) {
            throw new \Exception('Please Set Milk Item');
        }

        if ($request->filled('chalan_ids')) {

            foreach ($request->chalan_ids as $key => $chalan_id) {
                $amount = $request->input('chalan_amount_' . $chalan_id);
                $stockOutItem = StockOutItem::where('id', $chalan_id)->first();
                if ($stockOutItem->amount != $amount) {
                    $stockOut = StockOut::where('id', $stockOutItem->stock_out_id)->first();
                    $oldAmount = $stockOutItem->amount;
                    $stockOutItem->amount = $amount;
                    $stockOutItem->save();
                    maintainStockCenter($stockOutItem->item_id, $oldAmount, $stockOut->center_id, "out");
                    maintainStockCenter($stockOutItem->item_id, $oldAmount, $stockOut->from_center_id, "in");
                    maintainStockCenter($stockOutItem->item_id, $amount, $stockOut->center_id, "in");
                    maintainStockCenter($stockOutItem->item_id, $amount, $stockOut->from_center_id, "out");
                }
            }
        }
        if ($request->filled('center_ids')) {

            // dd($request->center_ids);
            foreach ($request->center_ids as $key => $center_id) {
                if ($request->filled('center_amount_' . $center_id)) {

                    $amount = $request->input('center_amount_' . $center_id);
                    if ($amount > 0) {

                        $stockOut = StockOut::create([
                            'date' => $date,
                            'center_id' => $maincenter->id,
                            'from_center_id' => $center_id,

                        ]);

                        $stockOutItem = StockOutItem::create([
                            'item_id' => $milk_id,
                            'amount' => $amount,
                            'stock_out_id' => $stockOut->id
                        ]);

                        maintainStockCenter($milk_id, $amount,  $maincenter->id, "in");
                        maintainStockCenter($milk_id, $amount, $center_id, "out");
                    }
                }
            }
        }
        return response('ok');
    }

    public function milkfatsnf(Request  $request)
    {
        if ($request->getMethod() == "POST") {
            $date = str_replace('-', '', $request->date);
            
            $milkDataType=$request->type==0?'m_amount':'e_amount';
            $milkDatas=DB::table('milkdatas')->where('center_id',$request->center_id)->where('date',$date)
            ->where($milkDataType,'>',0)
            ->select(DB::raw("user_id,id,{$milkDataType} as amount"))
            ->get();
            $userID=$milkDatas->pluck('user_id');
            $farmers=DB::table('users')
            ->whereIn('id',$userID)
            ->select('id','name','no')->get();

            $milkID=$milkDatas->pluck('id');
            $snfFats=DB::table('snffats')
            ->whereIn('milkdata_id',$milkID)
            ->where('session',$request->type)
            ->select(DB::raw("id,milkdata_id,snf,fat,user_id"))
            ->get();
            // dd($farmers,$milkDatas,$snfFats);
            return view('admin.milk.fatsnf.data',compact('farmers','milkDatas','snfFats'));
        } else {
            return view('admin.milk.fatsnf.index');
        }
    }

    public function milkfatsnfDel(Request $request)
    {

        $milkData=Milkdata::where('id',$request->milkdata_id)->first(['m_amount','e_amount','center_id']);
        $milk_id=env('milk_id',-1);
        $extracenters = explode(",", env('extracenter', ''));
        
        $amount=$request->type==0?$milkData->m_amount:$milkData->e_amount;
        
        if($milk_id>0 && $amount!=0 && !(in_array($request->center_id, $extracenters))){
            maintainStock($milk_id,$amount,$milkData->center_id,'out');
        }

        $milkDataType=$request->type==0?'m_amount':'e_amount';
        DB::update("update milkdatas set {$milkDataType}=0 where id=?",[$request->milkdata_id]);
        if($request->filled('snffat_id')){
            DB::delete('delete from snffats where id=?',[$request->snffat_id]);
        };
    }

    public function milkfatsnfSave(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')
            ->where('farmers.no', $request->user_id)
            ->where('farmers.center_id', $request->center_id)
            ->select('users.id', 'farmers.no', 'users.name', 'farmers.center_id')
            ->first();
        
        $milkStock=0;

        $milkData = Milkdata::where('user_id', $user->id)->where('date', $date)->first();
    
        if($milkData==null){
            $milkData = new Milkdata();
            $milkData->date = $date;
            $milkData->user_id = $user->id;
            $milkData->center_id = $request->center_id;

        }else{
            $milkStock=(-1)*($request->type==0?$milkData->m_amount:$milkData->e_amount);
        }

        if($request->type==1){
            $milkData->e_amount=$request->amt;

        }else{
            $milkData->m_amount=$request->amt;
        }
        $milkStock+=$request->amt;
        $milkData->save();

        $milk_id=env('milk_id',-1);
        $extracenters = explode(",", env('extracenter', ''));

        if($milk_id>0 && $milkStock!=0 && !(in_array($request->center_id, $extracenters))){
            maintainStock($milk_id,
            ($milkStock<0?(-1*$milkStock):$milkStock),
            $request->center_id,
            $milkStock<0?'out':'in');
        }


        $snffat=Snffat::where('user_id', $user->id)
        ->where('date', $date)
        ->where('milkdata_id', $milkData->id)
        ->where('session',$request->type)
        ->first();

        if($request->filled('snf') && $request->filled('fat')){
            if($snffat==null){
                $snffat=new Snffat();
                $snffat->user_id=$user->id;
                $snffat->date=$date;
                $snffat->session=$request->type;
                $snffat->center_id = $request->center_id;
                $snffat->milkdata_id = $milkData->id;
            }
            $snffat->snf=$request->snf;
            $snffat->fat=$request->fat;
            $snffat->save();
        }

        $milkData->amount=$request->type==0?$milkData->m_amount:$milkData->e_amount;

        return view('admin.milk.fatsnf.single',[
            'farmer'=>$user,
            'milkData'=>$milkData,
            'snfFat'=>isset($snffat)?$snffat:null
        ]);


    }


    //with name batch insert
    public function milkfatsnfname(Request $request)
    {   
        if($request->getMethod()=="POST"){
            $date = str_replace('-', '', $request->date);

            $farmers=DB::table('users')->join('farmers','farmers.user_id','=','users.id')
            ->where('farmers.center_id',$request->center_id)
            ->where('farmers.enabled',1)->select(DB::raw('users.id,users.no,users.name'))->orderBy('users.no')->get();

            $milkDataType=$request->type==0?'m_amount':'e_amount';
            $farmerIDS=$farmers->pluck('id');
            $milkDatas=DB::table('milkdatas')
            ->whereIn('user_id',$farmerIDS)
            ->where('date',$date)
            ->where($milkDataType,'>',0)
            ->select(DB::raw("id,user_id,{$milkDataType} as amount"))
            ->get();

            $milkDataIDS=$milkDatas->pluck('id');
            $snfFats=DB::table('snffats')->whereIn('milkdata_id',$milkDataIDS)
            ->where('session',$request->type)
            ->select(DB::raw('id,user_id,snf,fat'))
            ->get();
            $mode=$request->mode;
            return view('admin.milk.fatsnfname.data',compact('farmers','milkDatas','snfFats','mode'));

        }else{
            return view('admin.milk.fatsnfname.index');
        }
    }


    public function milkfatsnfnameSave(Request $request)
    {   
        $date = str_replace('-', '', $request->date);
        $milkStock=0;
        $user_id=$request->id;
        $milkData = Milkdata::where('user_id', $user_id)->where('date', $date)->first();
    
        if($milkData==null){
            $milkData = new Milkdata();
            $milkData->date = $date;
            $milkData->user_id = $user_id;
            $milkData->center_id = $request->center_id;

        }else{
            $milkStock=(-1)*($request->type==0?$milkData->m_amount:$milkData->e_amount);
        }

        if($request->type==1){
            $milkData->e_amount=$request->amount??0;

        }else{
            $milkData->m_amount=$request->amount??0;
        }
        $milkStock+=$request->amount??0;
        $milkData->save();

        $milk_id=env('milk_id',-1);
        $extracenters = explode(",", env('extracenter', ''));

        if($milk_id>0 && $milkStock!=0 && !(in_array($request->center_id, $extracenters))){
            maintainStock($milk_id,
            ($milkStock<0?(-1*$milkStock):$milkStock),
            $request->center_id,
            $milkStock<0?'out':'in');
        }


        $snffat=Snffat::where('user_id', $user_id)
        ->where('date', $date)
        ->where('milkdata_id', $milkData->id)
        ->where('session',$request->type)
        ->first();

        if($request->filled('snf') && $request->filled('fat')){
            if($snffat==null){
                $snffat=new Snffat();
                $snffat->user_id=$user_id;
                $snffat->date=$date;
                $snffat->session=$request->type;
                $snffat->center_id = $request->center_id;
                $snffat->milkdata_id = $milkData->id;
            }
            if($request->filled('snf')){

                $snffat->snf=$request->snf;
            }
            if($request->filled('fat')){

                $snffat->fat=$request->fat;
            }
            $snffat->save();
        }

        $deleted=false;
        if(!$request->filled('snf') && !$request->filled('fat') && $snffat!=null){
            $snffat->delete();
            $deleted=true;
        }

        $milkData->amount=$request->type==0?$milkData->m_amount:$milkData->e_amount;
        $deleted=$deleted || $snffat==null;
        return response()->json([
            'id'=>$user_id,
            'amount'=>$request->amount,
            'snf'=> $deleted?null:$snffat->snf,
            'fat'=>$deleted?null:$snffat->fat,
            'deleted'=>$deleted
        ]);
        
    }

}
