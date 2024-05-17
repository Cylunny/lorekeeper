<ul>
    <li class="sidebar-section">
        @foreach($creators as $creator)
        <div class="sidebar-item"><a href="{{ $creator->url }}">{{ $creator->name }}</a></div>
        @endforeach
    </li>
</ul>