@extends('admin.layouts.app')
@section('title','Employee Advance')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Employee Advance')
@section('toobar')
@endsection
@section('content')
<div class="row">
<div class="col-lg-12">

    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
        @csrf
        <div class="row">

            <div class="col-lg-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="nepali-datepicker" class="form-control next" data-next="u_id" changed="console.log('event')">
            </div>

            <div class="col-lg-4">
                <label for="u_number">Employee</label>

                <div class="form-group">
                   <select name="employee_id" id="employee_id" class="form-control show-tick ms select2">
                        <option ></option>
                        @foreach($emps as $employee)
                                   <option value="{{$employee->id}}">
                                       {{ $employee->user->name }}
                                    </option>
                        @endforeach
                   </select>
                </div>
            </div>

            <div class="col-lg-5">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control next" data-next="amount" placeholder="Enter title" required>
            </div>

            <div class="col-lg-3">
                <label for="amount">Advance Amount</label>
                <input type="number" id="amount" min="0" name="amount" class="form-control next xpay_handle" data-next="save" placeholder="Enter advance amount" value="0" required>
            </div>
            <div class="col-lg-2">
                <input type="submit" id="save" class="btn btn-raised btn-primary waves-effect btn-block" value="Add" style="margin-top:30px;">
            </div>
            <div class="col-12">
                <hr>
                <div class="row">
                    @include('admin.payment.take',['xpay_type'=>2]);
                </div>
            </div>

        </div>
    </form>
</div>
</div>
<hr>
<div class="pt-2 pb-2">
    <input type="text" id="sid" placeholder="Search">
</div>
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Employee</th>
                <th>Amount (Rs.)</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="advanceData">

        </tbody>
    </table>
</div>




@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    // initTableSearch('searchid', 'farmerforData', ['name']);
    // load by date
    $("input#nepali-datepicker").bind('click', function (e) {
        loadAdvance();
    });


    $("input#nepali-datepicker").bind('changed', function (e) {
        loadAdvance();
    });

    function loadAdvance(){
        var date = $('#nepali-datepicker').val();

        axios({
                method: 'post',
                url: '{{ route("admin.employee.advance.list")}}',
                data : {'date' : date}
            })
            .then(function(response) {
                // console.log(response.data);
                $('#advanceData').empty();
                $('#advanceData').html(response.data);
            })
            .catch(function(err) {
                //handle error
                console.log(err);
                showNotification('bg-danger',err.response.data);
        });
    }


    function initUpdate(id){
        win.showPost("Update Employee Advance","{{route('admin.employee.advance.edit')}}",{id:id},addEXPayHandle);
    }

    function update(ele,e,id){
        e.preventDefault();
        if(!expayVerifyData()){
            return;
        }
        
        axios.post(ele.action,new FormData(ele))
        .then((res)=>{
            $('#advancerow-'+id).replaceWith(res.data);
        })
        .catch((err)=>{
            showNotification('bg-danger',"Advance Not Updated");
        });

    }

    function del(id){
        var date = $('#nepali-datepicker').val();
        if (confirm('Are you sure?')) {
        axios({
                method: 'post',
                url: '{{ route("admin.employee.advance.del")}}',
                data : {
                    'date' : date,
                    'id':id
                }
            })
            .then(function(response) {
                showNotification('bg-success', 'Deleted successfully!');
                $('#advancerow-'+id).remove();
            })
            .catch(function(response) {
                //handle error
                console.log(response);
                showNotification('bg-danger', 'You have no authority!');
            });
        }
    }



    function saveData(e) {
        e.preventDefault();
        if($('#title').val()=="" || $('#amount').val()==0){
            alert('Please enter empty field!');
            $('#title').focus();
            return false;
        }else{
            if(!xpayVerifyData()){
                return;
            }
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("admin.employee.advance.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Employee advance added successfully!');
                $('#largeModal').modal('toggle');
                $('#advanceData').prepend(response.data);
                $('#u_id').val('');
                $('#amount').val(0);
                $('#u_id').focus();
                resetXPayment();

            })
            .catch(function(err) {
                //handle error
                console.log(err);
                showNotification('bg-danger',err.response.data);
            });

        }
    }


    window.onload = function() {
        var mainInput = document.getElementById("nepali-datepicker");
        mainInput.nepaliDatePicker();
        var month = ('0'+ NepaliFunctions.GetCurrentBsDate().month).slice(-2);
        var day = ('0' + NepaliFunctions.GetCurrentBsDate().day).slice(-2);
        $('#nepali-datepicker').val(NepaliFunctions.GetCurrentBsYear() + '-' + month + '-' + day);
        loadAdvance();
        $('#u_id').focus();
    };




</script>
@endsection
