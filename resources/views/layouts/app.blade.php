@auth
    @include('layouts.' . auth()->user()->role)
@endauth

@guest
    @include('layouts.guest')
@endguest

@push('styles')
    <style>
        .card > .card-img-top {
            height: 240px;
            object-fit: contain;
        }
    </style>
@endpush
