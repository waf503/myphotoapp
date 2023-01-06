<div class="card pub_image">
    <div class="card-header">
        @if($image->user->image)
        <div class="container-avatar">
            <a href="{{ route('profile', ['id'=>$image->user->id]) }}">
                <img src="{{ route('user.avatar', ['filename'=>$image->user->image]) }}" alt="avatar-user">
            </a>
        </div>

        @endif
        <div class="data-user">
            <a href="{{ route('profile',['id'=> $image->user->id]) }}">
                {{ $image->user->name.' '.$image->user->surname }}
                <span class="nickname">
                    {{ ' | @'.$image->user->nick }}
                </span>
            </a>
        </div>

    </div>
    <div class="card-body">
        <div class="image-container">
            <a href=" {{ route('image.detail',['id'=>$image->id]) }} ">
                <img src="{{ route('image.file', ['filename'=> $image->image_path]) }}" alt="">
            </a>
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
        <div class="comments">
            <a href="{{ route('image.detail',['id'=>$image->id]) }}" class="btn btn-sm btn-warning btn-comments">
                Comentarios {{ count ($image->comments) }}
            </a>
        </div>
    </div>
</div>