@if($user->image && $user->image != 'default.png')
    <span title="{{$user->name}}" class="avatar {{$class}}" data-toggle="tooltip" data-placement="top" >
        <img alt="{{ $user->name }}" src="{{ asset('storage/'.$user->image) }}" class="rounded-circle">
    </span>
@else
    <span title="{{$user->name}}" class="avatar {{$class}} avatar-{{$user->color}}" data-toggle="tooltip" data-placement="top" >{{ $user->initials }}</span>
@endif