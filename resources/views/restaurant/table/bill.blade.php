@extends('admin.print.app')
@section('content')
    <div class="text-center b-700 f-16">
        {{ env('companyName') }}
    </div>
    <div class="text-center b-700 f-14">
        {{ env('companyAddress') }}
    </div>
    <div class="text-center b-700 f-12">
        {{ env('companyphone') }}
    </div>

    <div class="row">
        @if (env('showpan',false))
            <div class="col-6 b-700 f-12 text-start">
            {{env('companyUseTax',false)?'VAT':'PAN'}} No: {{ env('companyVATPAN') }}
            </div>
        @endif
        @if (env('showreg',false))
        <div class="col-6 b-700 f-12 text-end">
            Reg No : {{ env('companyRegNO') }}
        </div>
            
        @endif
        <div class="col-12">
            <div class="line-1"></div>
        </div>
        <div class=" col-12 text-center b-700 f-14">
            {{ env('companyBillTitle') }}
        </div>
        <div class="col-4 b-500 f-12 text-start">
            Bill No: {{ $bill->id }}
        </div>
        <div class="col-8 b-500 f-12 text-end">
            Issued Date: {{_nepalidate( $bill->date )}}
        </div>
        <div class="col-12 b-500 f-12 text-start">
            Purchaser's Name : {{ $bill->name }}
        </div>
        <div class="col-6 b-500 f-12 text-start">
            Purchaser's Phone : {{ $bill->phone }}
        </div>
        @if (env('companyUseTax'))       
            <div class="col-6 b-500 f-12 text-end">
                Purchaser's Vat/PAN : {{ $bill->customer_pan }}
            </div>
        @endif
    </div>
    <table class="print-table f-12 text-start">
        <tr class="">
            <th>
                SN
            </th>
            <th>
                Item
            </th>
            <th>
                Qty
            </th>
            <th>
                Rate
            </th>


            <th>
                Total
            </th>
        </tr>
        @php
            $i = 1;
        @endphp
        @foreach ($bill->billItems as $item)
            <tr>
                <td>
                    {{ $i++ }}
                </td>
                <td>
                    {{ $item->name }}
                </td>
                <td>
                    {{ (float) $item->qty }}
                </td>
                <td>
                    {{ (float) $item->rate }}
                </td>
                <td>
                    {{ (float) $item->total }}
                </td>
            </tr>
        @endforeach
        @php
            $colspan=4;
        @endphp
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-end">Total:</th>
            <td>Rs. {{ (float) $bill->grandtotal }}</td>
        </tr>
        
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-end">Discount:</th>
            <td>Rs. {{ (float) $bill->dis }}</td>
        </tr>
        @if (env('companyUseTax', false))
            @if($bill->taxable>0 )
            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-end">Taxable:</th>
                <td>Rs. {{ (float) $bill->taxable }}</td>
            </tr>
            @endif
            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-end">Tax:</th>
                <td>Rs. {{ (float) $bill->tax }}</td>
            </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-end">GrandTotal:</th>
            <td>Rs. {{ (float) $bill->net_total }}</td>
        </tr>
        @if ($bill->paid > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-end">Paid:</th>
                <td>Rs. {{ (float) $bill->paid }}</td>
            </tr>
        @endif
        @if ($bill->due > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-end">Due:</th>
                <td>Rs. {{ (float) $bill->due }}</td>
            </tr>
        @endif
        @if ($bill->return > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-end">Return:</th>
                <td>Rs. {{ (float) $bill->return }}</td>
            </tr>
        @endif
        <tr class="no-border">
            <td colspan="{{$colspan+1}}">
                <div class="line-1"></div>
                <div class="f-12 b-700">
                    {{numberTowords($bill->grandtotal)}} Only|-
                </div>
                <div class="line-1"></div>
                {{-- <div class="f-12 b-700">
                    Printed By: <span class="b-500">{{$bill->user->name}}</span>
                </div>
                <div class="f-12 b-700">
                    Printed Time: <span id="time" class="b-500"></span>
                </div>
                <div class="f-12 b-700">
                    OC,#{{$bill->counter_name}}
                </div>
                @if ($bill->copy>1)
                <div class="f-12 b-700">
                    Copy {{$bill->copy}} Of Original
                </div>
                @endif --}}
                {{-- <div class="line-1"></div>
                <div class="f-12 b-700 text-center">
                    Goods sold will be exchanged within seven days.
                    <br>
                    Thank you, Please Visit Us Again!!
                </div> --}}
            </td>
        </tr>
    </table>
    <script>
        function getTime(){
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();
            if (minutes < 10){
                minutes = "0" + minutes;
            }
            if (seconds < 10){
                seconds = "0" + seconds;
            }
            var v = hours + ":" + minutes + ":" + seconds + " ";
            if(hours > 11){
                v+="PM";
            } else {
                v+="AM"
            }
            return v;
        }
        document.getElementById('time').innerText=getTime();
        setInterval(function(){
            document.getElementById('time').innerText=getTime();
         }, 2000);

    </script>
@endsection
