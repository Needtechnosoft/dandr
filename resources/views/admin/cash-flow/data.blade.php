{{-- <div class="row">
    <div class="col-md-6 mt-3">
        <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Particular</th>
                    <th>Outgoing (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Payment to farmer for milk</td>
                    <td>{{ $milkPayment }}</td>
                </tr>
                <tr>
                    <td>Advance given to farmer </td>
                    <td>{{ $farmerAdvance }}</td>
                </tr>
                <tr>
                    <td>Expenses </td>
                    <td>{{ $expense }}</td>
                </tr>
                <tr>
                    <td>Advance given to emplyee </td>
                    <td>{{ $empAdvance }}</td>
                </tr>
                <tr>
                    <td>Salary payment to employees </td>
                    <td>{{ $empSalary }}</td>
                </tr>
                @php
                    $outtot = $milkPayment + $farmerAdvance + $expense + $empAdvance + $empSalary
                @endphp
                <tr>
                    <td><strong>Total</strong></td>
                    <td>{{ $outtot }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <div class="table-responsive">
            <div style="border: 1px #ccc slid; padding:1rem;">
                <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>Particular</th>
                            <th>Incoming (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sell items to farmer </td>
                            <td>{{ $sellItems }}</td>
                        </tr>

                        <tr>
                            <td>Distributor Sell </td>
                            <td>{{ $distributorSell }}</td>
                        </tr>

                        @php
                            $incomtot =  $sellItems + $distributorSell
                        @endphp
                        <tr>
                            <td><strong>Total</strong></td>
                            <td>{{ $incomtot }}</td>
                        </tr>

                        <tr>
                            <td><strong>Total incoming - Total outgoing</strong></td>
                            <td>{{ $incomtot -$outtot }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div> --}}

<div>
    <table class="table-bordered table">
        <tr>
            <th>Particular</th>
            <th>Outgoing (Rs.)</th>
            <th>Incoming (Rs.)</th>
            <th>
                profit (Rs.)
            </th>
            <th>
                Loss (Rs.)
            </th>
        </tr>
        <tr>
            <td>Payment to farmer for milk</td>
            <td>{{ $milkPayment }}</td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td>Advance given to farmer </td>
            <td>{{ $farmerAdvance }}</td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td>Expenses </td>
            <td>{{ $expense }}</td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td>Advance given to emplyee </td>
            <td>{{ $empAdvance }}</td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td>Salary payment to employees </td>
            <td>{{ $empSalary }}</td>
            <td></td><td></td><td></td>
        </tr>
        @php
            $outtot = $milkPayment + $farmerAdvance + $expense + $empAdvance + $empSalary
        @endphp
        <tr>
            <td>Sell items to farmer </td>
            <td></td>
            <td>{{ $sellItems }}</td>
            <td></td>
            <td></td>

        </tr>

        <tr>
            <td>Distributor Sell </td>
            <td></td>
            <td>{{ $distributorSell }}</td>
            <td></td>
            <td></td>
        </tr>

        @php
            $incomtot =  $sellItems + $distributorSell;
            $profit= $incomtot -$outtot ;
        @endphp
        <tr>
            <td colspan="3"></td>
            @if ($profit>0)
            <td>
                {{$profit}}
            </td>
            <td></td>
            @elseif ($profit<0)
            <td></td>
            <td>
                {{(-1*$profit)}}
            </td>
            @else
            <td>--</td>
            <td>--</td>
            @endif
        </tr>
    </table>
</div>

