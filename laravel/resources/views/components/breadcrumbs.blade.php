@props(['items'])
<!-- принимаем массив элементов -->

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($items as $item)
        @if(!$loop->last)
        <li class="breadcrumb-item">
            <a href="{{ route('group.show', $item['id']) }}">{{ $item['name'] }}</a>
        </li>
        @else
        <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
        @endif
        @endforeach
    </ol>
</nav>