@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include('includes.message')
            <div class="card pub_image pub_image_detail">
                <div class="card-header">
                    @if($image->user->image)
                    <div class="container-avatar">
                        <a href="{{ route('profile', ['id'=>$image->user->id]) }}">
                            <img src="{{ route('user.avatar', ['filename'=>$image->user->image]) }}" alt="avatar profile">
                        </a>
                    </div>

                    @endif
                    <div class="data-user">
                        <a href="{{ route('profile', ['id'=>$image->user->id]) }}">
                            {{ $image->user->name.' '.$image->user->surname.' '.$image->user->id}}
                            <span class="nickname">
                                {{ ' | @'.$image->user->nick }}
                            </span>
                        </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="image-container">
                        <img src="{{ route('image.file', ['filename'=> $image->image_path]) }}" alt="">
                    </div>
                    <div class="description">
                        <span class="nickname">{{'@'.$image->user->nick.' | '.\Carbon\Carbon::now()->diffForHumans($image->created_at) }}</span>
                        <p>{{$image->description}}</p>
                    </div>
                    <div class="likes">
                        <?php $user_like = false; ?>
                        @foreach($image->likes as $like)
                        @if($like->user->id == Auth::user()->id)
                        <?php $user_like = true; ?>
                        @endif
                        @endforeach

                        @if($user_like)
                        <img class="btn-dislike" data-id=" {{ $image->id }} " src="{{ asset('img/heart-red.png') }}" alt="heart">
                        @else
                        <img class="btn-like" data-id=" {{ $image->id }} " src="{{ asset('img/heart-gray.png') }}" alt="heart">
                        @endif
                        {{ count($image->likes) }}
                    </div>
                    @if(Auth::user() && Auth::user()->id === $image->user->id)
                    <div class="actions">
                        <a href=" {{ route('image.edit', ['id'=>$image->id]) }} " class="btn btn-primary btn-sm">Actualizar</a>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Borrar
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirmación de eliminado</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Esta seguro de eliminar esta imagen?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                                <a class="btn btn-warning" href="{{ route('image.delete', ['id'=>$image->id]) }}">Borrar</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif

                    <div class="clearfix">

                    </div>
                    <div class="comments">
                        <h2>
                            Comentarios {{ count ($image->comments) }}
                        </h2>
                        <hr>
                        <form action=" {{ route('comment.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="image_id" value="{{ $image->id }}" />
                            <p>
                                <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" cols="10" rows="5">
                                </textarea>
                                @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </p>
                            <input type="submit" value="Enviar" class="btn btn-primary" />
                        </form>
                        <hr>
                        @foreach($image->comments as $comment)
                        <div class="comment">
                            <span class="nickname">{{'@'.$comment->user->nick.' | '.\Carbon\Carbon::now()->diffForHumans($comment->created_at) }}</span>
                            <p>
                                {{ $comment->content }}
                                <br />
                                @if(Auth::check() && ($comment->user_id == Auth::user()->id || $comment->image->user_id == Auth::user()->id))
                                <a class="btn btn-sm btn-danger" href="{{ route('comment.delete', ['id' => $comment->id]) }}">
                                    Eliminar
                                </a>
                                @endif
                            </p>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection