@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            @include('users.card', ['user' => $user])
        </aside>
        <div class="col-sm-8">
            <!--右上-->
            @include('users.navtabs', ['user' => $user])
            <!--右下-->
            @include('users.users', ['users' => $users])
        </div>
    </div>
@endsection