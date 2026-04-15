<div class="row">
    @foreach ($dataList as $list)
        <div class="col-12 text-left top-line2 font-weight-normal {{ $list['EXTRA_CLASS'] }} ">
            {{ $list['ID'] }}. {{ $list['NAME'] }}
        </div>
    @endforeach
</div>
