<div id="commentSection">
    @if ($comments->isNotEmpty())
        <ul>
            @foreach ($comments as $comment)
                <li>
                    <strong>{{ $comment->commenter_name }}:</strong> {{ $comment->comment }}<br>
                    <small>{{ $comment->created_at }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>Chưa có bình luận nào.</p>
    @endif
</div>
