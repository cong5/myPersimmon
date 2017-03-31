@foreach($navigation as $item)
    <li class="menu-item"><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
@endforeach