<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function table(Request $request)
    {
        if ($request->getMethod() == "POST") {
            DB::update('update tables set data=?  where id = ?', [scriptSafe($request->data), $request->id]);
        } else {
            $items = DB::select('select id,title,sell_price as rate,number  from items where posonly=1');
            $tables = DB::table('tables')->get();
            $sections = DB::table('sections')->get();
            return view('restaurant.table.index', compact('tables', 'sections', 'items'));
        }
    }

    public function bill(Request $request)
    {
        $id = $request->id;
        $table = Table::where('id', $id)->first();
        $datas = json_decode($table->data);
        // dd($datas);
        $items = [];
        $i = 1;
        foreach ($datas as $key => $data) {
            $item_id = $data->item->id;
            if (!isset($items['data_' . $item_id])) {
                $item = DB::selectOne('select sell_price as rate from items where id=?', [$item_id]);

                $items['data_' . $item_id] = (object)[
                    "id" => $item_id,
                    "name" => $data->item->title,
                    "rate" => $item->rate,
                    "qty" => $data->qty,
                    "total" => ($data->item->rate * $data->qty)
                ];
            } else {
                $current = $items['data_' . $item_id];
                $current->qty += $data->qty;
                $current->total = $current->qty  * $current->rate;
                $items['data_' . $item_id] = $current;
            }
        }

       return view('restaurant.table.billitem',compact('items'));
    }

    public function print(Request $request)
    {
        $bill=Bill::where('id',$request->id)->first();
        if($bill->table_id==null){
            echo "invalid bill";
        }else{  
            $bill->billitems=BillItem::where('bill_id',$bill->id)->get();
            return view('restaurant.table.bill',compact('bill'));
        }
    }

    // public function FunctionName()
    // {
    //     stat
    // }
}