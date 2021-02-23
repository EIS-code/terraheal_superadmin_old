<div class="center-top d-flex justify-content-between">
    <div class="title">{{ __('Cetners') }}</div>
    <div class="top-right">
        <div class="add-center"> <a href="{{ route('centers.create') }}">{{ __('Add Centers') }}</a> </div>
    </div>
</div>

<div class="center-list">
    <div class="center-inner">
        @if (!empty($shops) && !$shops->isEmpty())
            @foreach ($shops as $shop)
                <div class="center-col" onclick="location.href='{{ route("centers.get.info", $shop->id) }}'">
                    <div class="center-title">{{ $shop->name }}</div>
                    <div class="center-text">{{ $shop->address }}</div>
                    <div class="service-hour"> <span class="sh-sec service"><span class="ct">{{ $shop->totalServices }}+</span>{{ __('Services') }}</span> <span class="sh-sec hours"><i class="far fa-clock"></i>{{ __('Hours') }}</span> </div>
                    <div class="center-bottom"> <a href="#"><i class="fas fa-eye"></i></a> <a href="#"><i class="far fa-envelope"></i></a> </div>
                </div>
            @endforeach
        @else
            <div class="center-col" style="margin: 0 auto;">
                <div class="center-title">{{ __('No Shop') }}</div>
            </div>
        @endif
    </div>
</div>
