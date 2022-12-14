@extends('admin.layouts.app')
@section('title', 'Salary Pay')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />

@endsection
@section('head-title')
<a href="{{route('admin.employee.index')}}">Employees</a> / Employee Salary Payment
@endsection
@section('toobar')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div>
                <input type="text" name="emp" id="emp" class="w-100 py-1 px-2" placeholder="Search Employee">
            </div>
            <table class="table table-bordered" id="empdatas" >
                <tr>
                    <th>Employee</th>
                </tr>
                @foreach (\App\Models\Employee::all() as $employee)
                @if (isset($employee->user))
                <tr id="emp-{{ $employee->id }}"  data-name="{{ $employee->user->name }}" onclick="setEmp({{ $employee->id}})" style="cursor: pointer;">

                    <td >
                        {{ $employee->user->name }}
                    </td>
                </tr>
                @endif
                @endforeach
            </table>
        </div>
        <div class="col-lg-9">

            <form id="form_validation" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-3">
                        <label for="date">For Year</label>
                        <select name="year" id="year" class="form-control show-tick ms select2 load-year">

                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label for="date">For Month</label>
                        <select name="month" id="month" class="form-control show-tick ms select2 load-month">

                        </select>
                    </div>



                    <div class="col-lg-3">
                        <span class="btn btn-primary" onclick="loadEmployeeData();" style="margin-top:30px;">Load
                            Data</span>
                    </div>
                    <div class="col-lg-3">
                        <span class="btn btn-success" onclick="printDiv('data');" style="margin-top:30px;">Print</span>
                    </div>

                </div>
            </form>
            <div class="table-responsive">
                <div id="paid">

                </div>
                <div id="employeeData">

                </div>
            </div>
        </div>
    </div>






@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        initTableSearch('emp', 'empdatas', ['name']);
        // load by date



        function closeMonth(amount){
            
            data={"emp_id":emp_id,"year":$('#year').val(),"month":$('#month').val(),"amount":amount};
            if(confirm("Do You Want To Close Month")){
                showProgress("Closing Month For Employee");
                axios.post('{{route('admin.employee.account.close')}}',data)
                .then((res)=>{
                    loadEmployeeData();
                    hideProgress();
                    showNotification('bg-success',"Month CLosed Sucessfully");
                    
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger',err.response.data);
                })
            }
        }

        emp_id=0;
        function setEmp(id){
            emp_id=id;
            loadEmployeeData();
        }
        function loadEmployeeData() {

            var year = $('#year').val();
            var month = $('#month').val();
            if(emp_id==0){
                return;
            }
            if (emp_id == -1) {
                alert('Please select employee first');
                $('#employee_id').focus();
            } else {
                axios({
                        method: 'post',
                        url: '{{ route('admin.salary.load.emp.data') }}',
                        data: {
                            'emp_id': emp_id,
                            'year': year,
                            'month': month
                        }
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        $('#employeeData').empty();
                        $('#employeeData').html(response.data);
                        setDate("nepali-datepicker",false);
                        addXPayHandle();
                    })
                    .catch(function(response) {
                        //handle error
                        console.log(response);
                    });
            }
        }


        function salaryPayment() {
            var date = $('#nepali-datepicker').val();
            var year = $('#year').val();
            var month = $('#month').val();
            var pay = $('#p_amt').val();
            var desc = $('#p_detail').val();
            if (pay <= 0) {
                alert('You can not perform further action');
                return false;
            } else {
                if (confirm('Are you sure ?')) {
                    const data=loadXPay( {
                                'date': date,
                                'emp_id': emp_id,
                                'year': year,
                                'month': month,
                                'pay': pay,
                                'desc': desc,

                            });
                    console.log(data);
                    showProgress('Adding Salary Pay');
                    axios({
                            method: 'post',
                            url: '{{ route('admin.salary.save') }}',
                            data:data
                        })
                        .then(function(response) {
                            // console.log(response.data);
                            if (response.data == 'ok') {
                                showNotification('bg-success', 'Salary paid successfully!');
                            } else {
                                showNotification('bg-danger', 'Already paid !');
                            }
                            loadEmployeeData();
                            hideProgress();
                        })
                        .catch(function(err) {
                            //handle error
                            console.log(err);
                            if(err.response){
                                showNotification('bg-danger', 'Error : '+err.response.data);
                            }else{
                                showNotification('bg-danger', 'Some error occured please try again');

                            }
                            hideProgress();

                        });
                }
            }
        }

        // amount transfer
        function transferAmt() {
            var date = $('#nepali-datepicker').val();
            var emp_id = $('#employee_id').val();
            var year = $('#year').val();
            var month = $('#month').val();
            var transfer_amount = $('#transfer_amount').val();
            var desc = $('#p_detail').val();
            if (transfer_amount <= 0) {
                alert('please enter transfer amount');
                return false;
            } else {
                if (confirm('Are you sure ?')) {
                    showProgress('Transfering Amount');

                    axios({
                            method: 'post',
                            url: '{{ route('admin.employee.amount.transfer') }}',
                            data: {
                                'date': date,
                                'emp_id': emp_id,
                                'year': year,
                                'month': month,
                                'transfer_amount': transfer_amount,
                                'desc': desc
                            }
                        })
                        .then(function(response) {
                            // console.log(response.data);
                            if (response.data == 'ok') {
                                showNotification('bg-success', 'Salary paid successfully!');
                            } else {
                                showNotification('bg-danger', 'Already paid !');
                            }
                            $('#p_amt').val(0);
                            $('#transfer_amount').val(0);
                            paidList();
                            hideProgress();

                        })
                        .catch(function(response) {
                            //handle error
                            console.log(response);
                            hideProgress();

                        });
                }
            }
        }

        function paidList() {
            var year = $('#year').val();
            var month = $('#month').val();
            var emp_id = $('#employee_id').val();
            axios({
                    method: 'post',
                    url: '{{ route('admin.salary.list') }}',
                    data: {
                        'year': year,
                        'month': month,
                        'emp_id': emp_id
                    }
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#paid').empty();
                    $('#paid').html(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        window.onload = function() {

            // paidList();
        };


        $('#month').change(function() {
            // paidList();
            if ($('#employee_id').val() != -1) {
                loadEmployeeData();
            }
        });

        $('#employee_id').change(function() {
            loadEmployeeData();
        })

    </script>
@endsection
