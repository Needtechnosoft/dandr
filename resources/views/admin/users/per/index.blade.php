@extends('admin.layouts.app')
@section('title','User Permission')
@section('head-title','User Permission')
@section('content')
<form action="{{route('admin.user.per',['user'=>$user->id])}}" method="post">
    @csrf
    <span class="btn btn-primary" onclick="selectAll()">Select All</span>
    <span class="btn btn-danger" onclick="clearAll()">Clear All</span>
<hr>

    @foreach ($roles as $key=>$role)
        <div class="p-2 shadow mb-3">
            <h5 class="m-1 cap d-flex align-items-center justify-content-between">
                {{-- <input type="checkbox" id="{{$role['code']}}" name="codes[]" value="{{$role['code']}}" {{has_per($role['code'],$per)?"checked":""}}> --}}
                <span>
                    {{roleToWord($key)}}
                </span>
                <span>
                    <span class="btn btn-success" onclick="select('{{$key}}')">Select All</span>
                    <span class="btn btn-danger" onclick="clearSel('{{$key}}')">Clear All</span>
                </span>
            </h5>
            <hr class="m-1">
            @foreach ($role['children'] as $key_child=>$role_child)
               <div class="pl-3">
                   <input type="checkbox" class="check check-{{$key}}" id="{{$role_child['code']}}" name="codes[]" value="{{$role_child['code']}}" {{has_per($role_child['code'],$per)?"checked":""}}>
                   <label for=""></label><strong class="cap">{{roleToWord($key_child)}}</strong>
               </div>
            @endforeach
        </div>
    @endforeach
    <button class="btn btn-primary">
        Submit
    </button>
</form>

@endsection
@section('js')
    <script>
        function selectAll(){
            $('.check').each(function (index, element) {
                element.checked=true;

            });
        }
        function select(key){
            $('.check-'+key).each(function (index, element) {
                element.checked=true;

            });
        }
        function clearAll(){
            $('.check').each(function (index, element) {
                element.checked=false;

            });
        }
        function clearSel(key){
            console.log('.check-'+key);
            $('.check-'+key).each(function (index, element) {
                element.checked=false;

            });
        }
    </script>
@endsection
