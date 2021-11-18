@foreach($supplier as $user)
<tr id="supplier-{{$user->id}}" data-name="{{ $user->name }}" class="searchable">
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->phone }}</td>
    <td>{{ $user->address }}</td>
    <td>
       @if (auth_has_per('07.03'))
        <button  type="button" data-supplier="{{$user->toJson()}}" data-id="{{$user->id}}"  data-phone="{{ $user->address }}" class="btn btn-primary btn-sm" onclick="initEdit(this);" >Edit</button>
       @endif
        |
        <a href="{{ route('admin.supplier.detail',$user->id) }}" class="btn btn-primary btn-sm">View</a>
        |
        @if (auth_has_per('07.04'))
        <button class="btn btn-danger btn-sm" onclick="removeData({{$user->id}});">Delete</button></td>
        @endif
</tr>
@endforeach
