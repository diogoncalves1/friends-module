<form action="{{ route('api.friendships.accept', 1) }}" method="post">
    @csrf
    @method('POST')
    <input type="submit">
</form>