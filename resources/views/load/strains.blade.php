@foreach($strains as $strain)
    <div class="strain-item" data-strain="{{ json_encode($strain) }}" data-href="{{ route($route, ['id'=>$strain->id]) }}">           
            <div class="search-content">
                <p>{{$strain->name}}</p>
                <span style="font-size: 14px; font-weight:600; display:block;   overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;  ">{{ $strain->effect }}</span>
            </div>    
    </div>
@endforeach