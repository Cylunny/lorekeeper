<ul>
    <li class="sidebar-header"><a href="/character-creator" class="card-link">Character Creators</a></li>
    <li class="sidebar-section">
        @foreach($creators as $creator)
        <div class="sidebar-item"><a href="{{ $creator->url }}">{{ $creator->name }}</a></div>
        @endforeach
    </li>
</ul>