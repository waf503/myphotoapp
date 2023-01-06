@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-user">
                
                @if($user->image)
                    <div class="container-avatar">
                        <img src="{{ route('user.avatar', ['filename'=>$user->image]) }}" alt="user avatar">
                    </div>
                @endif
                
                <div class="user-info">
                    <h1>{{'@ '.$user->nick }}</h1>
                    <h1>{{ $user->name.' '.$user->surname }}</h1>
                    <p>{{ $user->description }}</p>
                </div>  
                <div class="clearfix">

                </div>   
                                         
            </div>
            @foreach($user->images as $image)
                @include('includes.image',['image'=>$image])
            @endforeach
        </div>
    </div>
</div>
@endsection
