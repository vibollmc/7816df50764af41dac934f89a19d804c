@if(isset($breadcrumb))
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{route('home')}}">Home</a></li>
    @foreach($breadcrumb as $item)
        <li>
            @if($item['link'] == 'javascript:void(0)')
            {!!$item['title']!!}
            @else
            <a href="{{$item['link']}}">{!!$item['title']!!}</a>
            @endif
        </li>
    @endforeach
</ul>
@endif