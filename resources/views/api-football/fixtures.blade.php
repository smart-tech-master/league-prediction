@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    {{--<nav class="mb-3">--}}
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        @isset($rounds)
                            @foreach($rounds as $round)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->iteration == 1 ? ' active' : null }}"
                                        id="round-{{ $round->id }}-tab" data-toggle="tab" href="#round-{{ $round->id }}"
                                        role="tab" aria-controls="round-{{ $round->id }}"
                                        aria-selected="{{ $loop->iteration == 1 ? 'true' : 'false' }}">{{ $round->name }}</a>
                                </li>
                            @endforeach
                        @endisset
                    </ul>
                    {{--</nav>--}}
                    <div class="tab-content" id="nav-tabContent">
                        @isset($rounds)
                            @foreach($rounds as $round)
                                <div class="tab-pane fade show active"  id="round-{{ $round->id }}" role="tabpanel" aria-labelledby="round-{{ $round->id }}-tab">
                                    @auth
                                        <form action="{{ route('predictions.store') }}" method="post">
                                            @csrf
                                            @endauth

                                            @foreach($round->fixtures()->orderBy('timestamp')->get() as $fixture)
                                                @php
                                                    $homeTeam = $fixture->teams()->wherePivot('ground', 'home')->first();
                                                    $awayTeam = $fixture->teams()->wherePivot('ground', 'away')->first();
                                                @endphp

                                                @auth
                                                    @php
                                                        $goals = auth()->user()->predictions()->whereLeagueId($fixture->league->id)->whereSeasonId($fixture->season->id)->whereRoundId($fixture->round->id)->whereFixtureId($fixture->id)->first();
                                                    @endphp
                                                @endauth

                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-2">{{ $loop->iteration }}</div>
                                                            <div class="col-md-4 text-left">
                                                                <img src="{{ $fixture->league_id == 1 ? $homeTeam->flag : $homeTeam->logo }}" width="50"/>
                                                                {{ $homeTeam->name }}
                                                                @auth
                                                                    <input type="number"
                                                                            class="form-control @error('predictions.' . $fixture->id . '.goals.home') is-invalid @enderror"
                                                                            name="predictions[{{ $fixture->id }}][goals][home]"
                                                                            value="{{ old('predictions.' . $fixture->id . '.goals.home') ?: ($goals->home_team_goals ?? null) }}">
                                                                    @include('shared.errors.validation', ['name' => 'predictions.' . $fixture->id . '.goals.home'])
                                                                @endauth

                                                                {{ $homeTeam->pivot->goals }}
                                                            </div>
                                                            <div
                                                                class="col-md-2 text-center">{{ $fixture->timestamp->format(config('api-football.fixture_date_format')) }}</div>
                                                            <div class="col-md-4 text-right">
                                                                <img src="{{ $fixture->league_id == 1 ? $awayTeam->flag : $awayTeam->logo }}" width="50"/>
                                                                {{ $awayTeam->name }}
                                                                @auth
                                                                    <input type="number"
                                                                            class="form-control @error('predictions.' . $fixture->id . '.goals.away') is-invalid @enderror"
                                                                            name="predictions[{{ $fixture->id }}][goals][away]"
                                                                            value="{{ old('predictions.' . $fixture->id . '.goals.away') ?: ($goals->away_team_goals ?? null) }}">
                                                                    @include('shared.errors.validation', ['name' => 'predictions.' . $fixture->id . '.goals.away'])
                                                                @endauth

                                                                {{ $awayTeam->pivot->goals }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @auth
                                                <button class="btn btn-primary btn-block" type="submit">Submit
                                                    Prediction
                                                </button>
                                            @endauth

                                            @auth
                                        </form>
                                    @endauth
                                </div>
                            @endforeach
                        @endisset
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
