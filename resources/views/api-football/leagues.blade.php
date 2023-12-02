@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    @if(isset($leagues) && isset($season))
                        @foreach($leagues as $league)
                            <div class="col-md-4">
                                <div class="card float-left" style="width: 18rem;">
                                    <img class="card-img-top" src="{{ $league->logo }}" alt="{{ $league->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $league->name }}</h5>
                                        <p class="card-text">Season: {{ $season->year }}</p>
                                        <a href="{{ route('api-football.leagues.seasons.fixtures.index', [$league, $season]) }}"
                                           class="btn btn-primary">Fixtures</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
