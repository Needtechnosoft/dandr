<style>
    td,
    th {
        border: 1px solid black !important;
        padding: 2px !important;
        font-weight: 600 !important;
    }

</style>
<hr>
<div class="p-2" id="data">
    <h4 >
        Salary Seet For {{$employee->user->name}} , {{$year}} - {{nepaliMonthName($month)}}
    </h4>
    <hr>
    <table class="table">
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>CR</th>
            <th>DR</th>
            <th>Balance</th>
        </tr>
        @if ($prev != 0)

            <tr>
                <td>
                    --
                </td>
                <td>
                    Previous Balance
                </td>
                @if ($prev > 0)

                    <td>

                    </td>
                    <td>
                        {{ $prev }}
                    </td>
                    <td>
                        Dr.{{ $prev }}
                    </td>
                @elseif ($prev<0) <td>
                        {{ -1 * $prev }}
                        </td>
                        <td>

                        </td>
                        <td>
                            Cr.{{ -1 * $prev }}
                        </td>
                    @else
                        <td>
                            --
                        </td>
                        <td>
                            --
                        </td>
                        <td>
                            --
                        </td>
                @endif

            </tr>
        @endif
        @foreach ($arr as $l)
            <tr>
                <td>{{ _nepalidate($l->date) }}</td>
                <td>{!! $l->title !!}</td>

                <td>
                    @if ($l->type == 1)
                        {{ (float) $l->amount }}
                    @endif
                </td>
                <td>
                    @if ($l->type == 2)
                        {{ (float) $l->amount }}
                    @endif
                </td>
                <td>
                    @if ($l->amt > 0)

                        Dr. {{ (float) $l->amt }}
                @elseif ($l->amt<0) Cr. {{ (float) (-1 * $l->amt) }} @else -- @endif
            </td>

        </tr>
    @endforeach
    @if ($salaryLoaded)
        @php
            $remaning=$track;
        @endphp
    @else

            @php
                $sal=$salary[1]-$salary[0];
                $remaning =   $track - $sal;

            @endphp
        @if ($empSession == null && $sal>0)
            <tr>
                <td></td>
                <td>
                    Salary For This Month 
                    @if (env('use_employeetax',false))
                        ( -{{env('emp_tax',1)}}% tax)
                    @endif
                </td>
                <td>
                    {{ $sal }}
                </td>
                <td>
                </td>
                <td>
                    @if ($remaning == 0)
                        No Payable and Transfarable
                    @else
                        {{ $remaning >= 0 ? 'Dr.' : 'Cr.' }} {{ $remaning < 0 ? -1 * $remaning : $remaning }}
                    @endif

                </td>
            </tr>
        @endif
    @endif
</table>
</div>
<hr>

@if ($remaning < 0 && $empSession==null)
    <div class="p-2">
        <div class="row">
            <div class="col-md-4">
                <label for="date">Date</label>
                <input readonly type="text" name="date" id="nepali-datepicker" class="form-control"
                    placeholder="Date" value="{{$lastdate}}">
            </div>
            <div class="col-md-4">
                <label for="total"> Monthly Salary </label>
                <input type="text" id="salary" class="form-control" value="{{ $employee->salary }}" readonly>
            </div>
            <div class="col-md-4">
                <label for="pay">Pay Salary </label>
                <input type="text" class="form-control xpay_handle" id="p_amt" name="salary" min="0" step="0.001"
                    value="{{ (-1*$remaning) }}">
            </div>
            <div class="col-md-12 mt-1">
                <label for="detail">Payment Detail</label>
                <input type="text" class="form-control" id="p_detail" placeholder="Payment details">
            </div>

            <div class="col-12 pt-2">
            </div>
            <div class="col-md-3 pt-2">
                <input checked type="checkbox" name="close" id="close" value="1"> <label for="close">Close Month</label>
            </div>
            <div class="col-md-3">
                <span class="btn btn-primary btn-block"  onclick="salaryPayment();"> Pay Now
                </span>
            </div>
            <div class="col-12 pt-2">
                <div class="row">
                    @include('admin.payment.take',['xpay_type'=>2]);
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($empSession==null)
        <div class="p-2">
            <hr>
            <button class="btn btn-primary w-25" onclick="closeMonth({{$salaryLoaded?0:$sal}})">Close Month</button>
        </div>
    @endif

