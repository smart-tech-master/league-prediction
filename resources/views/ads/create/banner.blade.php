@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-bullhorn"></i> Ads Banner
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm" action="{{route('ads.store', ['type' => request()->type])}}" method="post"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group file-upload">
                            <input type="file" name="file" id="file-upload"/>
                            <label for="file-upload">
                                <img src="img/upload-icon.svg" alt="upload icon"/>
                                <p>choose between the choices picture or Video or Gif Picture</p>
                                <span class="prediciton-btn">Upload a file</span>
                            </label>

                            @include('shared.errors.validation', ['name' => 'file'])
                        </div>

                        <div class="form-group">
                            <label for="">Time Of Ad Appearance</label>
                            <select name="time_of_appearance" id="">
                                <option value="">Select any</option>
                                @for($i = 3; $i <= 15; $i++)
                                    <option value="{{ $i }}" {{ old('time_of_appearance') == $i ? ' selected' : null }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @include('shared.errors.validation', ['name' => 'time_of_appearance'])
                        </div>


                        <div class="form-group">
                            <label for="">Link Type</label>

                            <div class="selectbox no-bg">
                                <select name="link_type" id="">
                                    <option value="">Select any</option>
                                    <option value="external" {{ old('link_type') == 'external' ? ' selected' : null }}>
                                        External
                                    </option>
                                    <option value="internal" {{ old('link_type') == 'internal' ? ' selected' : null }}>
                                        Internal
                                    </option>
                                </select>
                                @include('shared.errors.validation', ['name' => 'link_type'])
                            </div>
                        </div>

                        <div class="form-group adLink">
                            <label for="">Ad Link</label>
                            <input type="text" name="link_external" class="form-control-sm form-control"
                                   value="{{ old('link_external') }}"/>
                            <select name="link_internal">
                                <option value="">Select any</option>
                                @foreach((new \App\Services\AdService())->getAppInternalPages() as $key => $value)
                                    <option value="{{ $key }}" @selected(old('link_internal') == $key)>{{ $value }}</option>
                                @endforeach
                            </select>
                            @include('shared.errors.validation', ['name' => 'link_external'])
                            @include('shared.errors.validation', ['name' => 'link_internal'])
                        </div>
                    </div>

                    <div class="col-xl-6 d-flex flex-column">
                        <div class="form-group">
                            <label for="">Location</label>

                            <div class="selectbox no-bg">
                                <select name="countries[]" id="countries" multiple="multiple">
                                    {{--                                    <option value="">Select country</option>--}}
                                    @isset($countries)
                                        @foreach($countries as $country)
                                            <option
                                                value="{{$country->id}}" {{ in_array($country->id, (old('countries') ?? [])) ? ' selected' : null }}>{{$country->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                <input type="checkbox" id="selectAllCountries">
                                <label for="selectAllCountries">Select All</label>
                                @include('shared.errors.validation', ['name' => 'countries'])
                            </div>
                        </div>

                        <div class="form-group date-group">
                            <label for="">Date Duration</label>

                            <div class="from">
                                <span>from</span>
                                <input type="date" name="started_at" value="{{ old('started_at') }}"/>
                            </div>
                            @include('shared.errors.validation', ['name' => 'started_at'])
                            <div class="to">
                                <span>to</span>
                                <input type="date" name="ended_at" value="{{ old('ended_at') }}"/>
                            </div>
                            @include('shared.errors.validation', ['name' => 'ended_at'])

                        </div>

                        <div class="btn-area">
                            <button type="submit" class="prediciton-btn">Add</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            $("#countries").select2();
            $("#selectAllCountries").click(function () {
                if ($("#selectAllCountries").is(':checked')) {
                    $("#countries > option").prop("selected", "selected");
                    $("#countries").trigger("change");

                } else {
                    $("#countries > option").prop("selected", "");
                    $("#countries > option").removeAttr("selected");
                    $("#countries").trigger("change");
                }
            });

            $('.adLink > input[name="link_external"]').hide();
            $('.adLink > select[name="link_internal"]').hide();

            $(document).on('change', 'select[name="link_type"]', function () {
                switch ($(this).val()) {
                    case 'internal':
                        $('.adLink > input[name="link_external"]').hide();
                        $('.adLink > select[name="link_internal"]').show();
                        break;
                    case 'external':
                        $('.adLink > input[name="link_external"]').show();
                        $('.adLink > select[name="link_internal"]').hide();
                        break;
                }
            });

            $('select[name="link_type"]').trigger('change');
        });
    </script>
@endpush
