@if(Auth::user()->image)
    <div class="container-avatar">
        <img alt="avatar" class="avatar" src="{{ route('user.avatar',['filename'=>Auth::user()->image]) }}" />                             
    </div>
@endif