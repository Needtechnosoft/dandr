<div>
    <table class="w-100">
        <tr>
            <th class="text-end px-2">Name:</th><td>{{$user->name}}</td>
        </tr>
        <tr>
            <th class="text-end px-2">Address:</th><td>{{$user->address}}</td>
        </tr>
        <tr>
            <th class="text-end px-2">Phone:</th><td>{{$user->phone}}</td>
        </tr>
        <tr>
            <th class="text-end px-2">Current Balance:</th>
            <td>
                @if ($balance!=0)
                    {{$balance>0?'DR. '.$balance:''}}
                    {{$balance<0?'CR. '.(-1*$balance):''}}
                @else
                    0
                @endif

            </td>
        </tr>
    </table>


</div>
<hr>
<div>
    <table class="table table-bordered">
        <tr>
            <th>
                Date
            </th>
            <th>
                Amount (Rs.)
            </th>
            <th>

            </th>
        </tr>
        @foreach ($payments as $payment)
            <tr>
                <td>{{_nepalidate($payment->date)}}</td>
                <td>{{$payment->amount}}</td>
                <td>
                    <button class="btn btn-danger" onclick="delPayment({{$payment->foreign_key??-1}},{{$payment->id}})">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
    </table>
</div>
<hr>
<div>
    <form action="{{route('admin.customer.payment.add')}}" method="post" id="addPayment" onsubmit="return addPayment(event)">
        @csrf
        <input type="hidden" name="id" value="{{$user->id}}">
        <div class="row">
            <div class="col-md-6">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control" min="0" required>
            </div>
            <div class="col-md-6">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control xpay_handle" min="0" required>
            </div>
            <div class="col-md-12">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="row my-1">
            @include('admin.payment.take')
            <div class="col-md-3">
                <button class="btn btn-primary">Add Payment</button>
            </div>
        </div>
    </form>
</div>
