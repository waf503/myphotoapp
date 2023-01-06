@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Burcar Gente</h2>
            <form id="buscador" action=" {{ route('user.index') }} ">
                <div class="row">
                    <div class="form-group col">
                        <input type="text"  id="search" class="form-control">                    
                    </div>
                    <div class="form-group col">
                        <input type="submit" value="Buscar" class="btn btn-success">
                    </div>
                </div>                
            </form>
            <br>
            <div class="clearfix">

                    </div>   
            @foreach($users as $user)
                <div class="profile-user">
                    
                    @if($user->image)
                        <div class="container-avatar">
                            <img src="{{ route('user.avatar', ['filename'=>$user->image]) }}" alt="user avatar">
                        </div>
                    @endif
                    
                    <div class="user-info">
                        <h2>{{'@ '.$user->nick }}</h2>
                        <h3>{{ $user->name.' '.$user->surname }}</h3>
                        <p>{{ $user->description }}</p>
                        <a class="btn btn-success btn-sm" href="{{ route('profile', ['id'=> $user->id])  }}">Ver perfil</a>
                    </div>  
                    <div class="clearfix">

                    </div>   
                                            
                </div>
            @endforeach
            <div class="row">
                {{ $users->links() }}
            </div>
        
        </div>
        <!--Paginacion-->
    </div>
</div>
@endsection
