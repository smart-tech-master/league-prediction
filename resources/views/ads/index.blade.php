@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header common_bg d-inline-flex justify-content-between align-items-center">
                        <div>{{ request()->type == 'launch-screen' ? 'Launch Screen Ads' : null }} {{ request()->type == 'banner' ? 'Ads Banner' : null }} {{ request()->type == 'tutorial-screen' ? 'Tutorials Screen' : null }}</div>
                        <div>
                            @if(request()->type != 'launch-screen')
                                <a class="prediciton-btn" href="{{ route('ads.create', ['type' => request()->type]) }}">
                                    <img src="img/plus.svg">
                                    Create new
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">

                        @if(request()->type == 'banner' || request()->type == 'logo')
                            <div class="selectbox no-bg">
                                <select name="countries[]" id="countries" multiple="multiple">
                                    <option value="">Select country</option>
                                    @isset($countries)
                                        @foreach($countries as $country)
                                            <option
                                                value="{{$country->id}}" {{ in_array($country->id, (old('countries') ?? [])) ? ' selected' : null }}>{{$country->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @include('shared.errors.validation', ['name' => 'countries'])
                            </div>

                            @if(request()->type == 'logo')
                                <div class="selectbox no-bg">
                                    <select name="leagues[]" id="leagues" multiple="multiple">
                                        <option value="">Select league</option>
                                        @isset($leagues)
                                            @foreach($leagues as $league)
                                                <option
                                                    value="{{$league->id}}" {{ in_array($league->id, (old('leagues') ?? [])) ? ' selected' : null }}>{{$league->name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @include('shared.errors.validation', ['name' => 'leagues'])
                                </div>
                            @endif

                            <button class="prediciton-btn" id="btnSearch">
                                Search
                            </button>
                        @endif

                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">SL</th>
                                @if(in_array(request()->type, ['launch-screen', 'banner']))
                                    <th scope="col">File</th>
                                    <th scope="col">Link</th>
                                    <th scope="col" style="width: 120px">Link Type</th>
                                    <th scope="col">Status</th>
                                @else
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                @endif
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @isset($ads)
                                @foreach($ads as $ad)
                                    <tr>
                                        @if(in_array(request()->type, ['launch-screen', 'banner']))
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                <img src="{{ $ad->file }}" width="50"/>
                                            </td>
                                            <td style="word-break: break-all">{{ $ad->link }}</td>
                                            <td style="width: 120px">{{ $ad->link_type }}</td>
                                            <td style="word-break: break-all">{{ $ad->banner_status }}</td>
                                        @else
                                            <th scope="row">{{ $ad->sl }}</th>
                                            <td>
                                                <img src="{{ $ad->file }}" width="50"/>
                                            </td>
                                            <td style="width: 120px">{{ $ad->title }}</td>
                                        @endif


                                        <td>
                                            <a href="{{ route('ads.edit', [$ad]) }}"
                                               class="btn btn-sm btn-info">Edit</a>

                                            @if(request()->type != 'launch-screen')
                                                <a class="btn btn-sm btn-danger"
                                                   href="{{ route('ads.destroy', [$ad]) }}"
                                                   onclick="event.preventDefault();
                                           document.getElementById('delete-form-{{ $ad->id }}').submit();"
                                                >{{ ! $ad->trashed() ? 'Delete' : 'Restore' }}</a>

                                                <form id="delete-form-{{ $ad->id }}"
                                                      action="{{ route('ads.destroy', [$ad]) }}"
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            @if(request()->type == 'banner')
                $(document).on('click', '#btnSearch', function () {
                    var countries = [];
                    $('select[name="countries[]"] option:selected').each(function() {
                        countries.push($(this).val());
                    });
                    let data = {
                        countries: countries
                    }
                    window.location = '{{ route('ads.index', ['type' => 'banner']) }}' + (data.countries.length !== 0 ? ('&' + $.param(data)) : null);
                    
                });
            @endif

            @if(request()->type == 'logo')
                $(document).on('click', '#btnSearch', function () {
                    var countries = [];
                    $('select[name="countries[]"] option:selected').each(function() {
                        countries.push($(this).val());
                    });
                    let data = {
                        countries: countries
                    }
                    var wLocation = '{{ route('ads.index', ['type' => 'logo']) }}' + (data.countries.length !== 0 ? ('&' + $.param(data)) : null);

                    var leagues = [];
                    $('select[name="leagues[]"] option:selected').each(function() {
                        leagues.push($(this).val());
                    });
                    data = {
                        leagues: leagues
                    }
                    wLocation = wLocation + (data.leagues.length !== 0 ? ('&' + $.param(data)) : "");

                    window.location = wLocation;
                });
            @endif
        })
    </script>
@endpush

