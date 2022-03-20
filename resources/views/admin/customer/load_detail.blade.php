<style>
    .d-print-show{
        display:none !important;
    }

</style>
<div class="row">

    <div class="col-md-12 mt-3">
        <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">
            <strong>Ledger</strong> <span class="btn btn-success" onclick="printDiv('ledger');">Print</span>
            <hr>

            <div id="ledger">
                <div class="d-print-show">
                    <style>
                        @media print {
                            td{
                                font-size: 1.2rem !important;
                                font-weight: 600 !important;
                            }


                            th:last-child, td:last-child {
                                display: none;
                            }

                        }
                        td,th{
                            border:1px solid black !important;
                            padding:2px !important;
                            font-weight: 600 !important;
                        }

                        table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        thead {display: table-header-group;}
                        tfoot {display: table-header-group;}
                        .d-show-rate{

                            @if(env('showdisrate',0)==1)
                                display:inline;
                            @else
                                display:none !important;
                            @endif
                        }
                    </style>
                    <h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
                        {{env('APP_NAME','Dairy')}} <br>

                    </h2>

                    <div style="font-weight:800;text-align:center;">
                        <span class="mx-3">  Ledger For : {{$user->name}} , </span>
                        {!!$title!!}
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" >
                    <tr>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Cr. (Rs.)</th>
                        <th>Dr. (Rs.)</th>
                        <th>Balance (Rs.)</th>
                        <th></th>
                    </tr>
                    @if ($prev!=0)
                        <tr>
                            <td>--</td>
                            <td>
                                Previous Balance
                            </td>
                            <td>
                                {{$prev<0?(-1*$prev):''}}
                            </td>
                            <td>
                                {{$prev>0?$prev:''}}

                            </td>
                            <td>
                                {{$prev<0?'CR. '.(-1*$prev):''}}
                                {{$prev>0?'DR. '.($prev):''}}
                            </td>
                        </tr>
                    @endif

                    @foreach ($ledgers as $l)
                        <tr data-id="ledger{{$l->id}}">
                            <td>{{ _nepalidate($l->date) }}</td>
                            <td>{!! $l->title !!}</td>

                            <td>
                                @if ($l->type==1)
                                    {{ rupee((float)$l->amount) }}
                                @endif
                            </td>
                            <td>
                                @if($l->type==2)
                                {{ rupee((float)$l->amount) }}
                                @endif
                            </td>
                            <td>
                                {{$l->amt>0?"Dr. ".$l->amt : "Cr. ".(-1*$l->amt)}}
                            </td>
                            <td>
                                {{-- @if ( $l->identifire==119)
                                    <button onclick="initLedgerChange(this);"  data-ledger="{{$l->toJson()}}">Edit</button>
                                @elseif($l->identifire==105)
                                    @if ($l->getForeign()!=null)
                                        <button onclick="sellLedgerChange(this);" data-foreign="{{$l->getForeign()->toJson()}}"  data-ledger="{{$l->toJson()}}">Edit</button>
                                    @endif
                                @elseif($l->identifire==114)
                                    @if($l->getForeign()!=null)
                                        <button onclick="payLedgerChange(this);" data-foreign="{{$l->getForeign()->toJson()}}"  data-ledger="{{$l->toJson()}}">Edit</button>
                                    @endif
                                @endif --}}
                                @if($l->identifire==130)
                                    {{-- <button  onclick="initEditLedger('{{$l->title}}',{{$l->id}});">Edit</button>
                                    <button  onclick="deleteLedger({{$l->id}},loadData);">Delete</button> --}}
                                    <button  onclick=" win.showPost('Bill Detail  ' , '{{route('admin.pos.billing.detail')}}', {id: {{$l->foreign_key}}});">Detail</button>
                                @else
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

