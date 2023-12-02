@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
            <span>
            <i class="fas fa-envelope"></i> Notifications
            </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm" action="{{route('messages.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control-sm form-control"
                                   value="{{ old('title') }}"/>
                            @include('shared.errors.validation', ['name' => 'title'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Text</label>
                            <textarea type="text" name="text"
                                      class="form-control-sm form-control">{{ old('text') }}</textarea>
                            @include('shared.errors.validation', ['name' => 'text'])
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group file-upload">
                            <input type="file" id="file-upload" name="picture"/>
                            <label for="file-upload">
                                <img src="img/upload-icon.svg" alt="upload icon"/>
                                <p>choose the picture</p>
                                <span class="prediciton-btn">Upload a file</span>
                            </label>
                            @include('shared.errors.validation', ['name' => 'picture'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Send To</label>

                            <div class="selectbox no-bg">
                                <select name="send_to" id="send_to">
                                    <option value="users" {{ old('send_to') == 'users' ? ' selected' : null }}>All
                                        Users
                                    </option>
                                    <option value="countries" {{ old('send_to') == 'countries' ? ' selected' : null }}>
                                        Country
                                    </option>
                                    <option value="leagues" {{ old('send_to') == 'leagues' ? ' selected' : null }}>
                                        League
                                    </option>
                                </select>
                                @include('shared.errors.validation', ['name' => 'send_to'])
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group countries-selectbox">
                            <label for="">Countries</label>

                            <!--<div class="selectbox no-bg">-->
                            <select class="form-select" name="countries[]" id="countries" multiple="multiple">
{{--                                <option value="">Select</option>--}}
                                @isset($countries)
                                    @foreach($countries as $country)
                                        <option
                                            value="{{ $country->id }}" {{ in_array($country->id, old('countries') ?: []) ? ' selected' : null }}>{{ $country->name }}</option>
                                    @endforeach
                                @endisset
                            </select>

                            <input type="checkbox" id="selectAllCountries">
                            <label for="selectAllCountries">Select All</label>
                            @include('shared.errors.validation', ['name' => 'countries'])
                            <!--</div>-->
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group leagues-selectbox">
                            <label for="">Leagues</label>

                            <div class="selectbox no-bg">
                                <select class="form-select" name="leagues[]" id="leagues" multiple="multiple">
{{--                                    <option value="">Select</option>--}}
                                    @isset($leagues)
                                        @foreach($leagues as $league)
                                            <option
                                                value="{{ $league->id }}" {{ in_array($league->id, old('leagues') ?: []) ? ' selected' : null }}>{{ $league->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>

                                @include('shared.errors.validation', ['name' => 'leagues'])
                            </div>
                        </div>
                    </div>

                </div>

                <style>
                    .leagues-selectbox,
                    .countries-selectbox {
                        display: none;
                    }
                </style>

                <div class="row">
                    <div class="col-md-12">

                        <div class="btn-area">
                            <button type="submit" class="prediciton-btn">Send</button>
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
            $('#send_to').on('change', function () {
                var selectedVal = $(this).find(":selected").val();

                if ('countries' == selectedVal) {
                    $('.countries-selectbox').show();
                    $('.leagues-selectbox').hide();

                } else if ('leagues' == selectedVal) {
                    $('.countries-selectbox').hide();
                    $('.leagues-selectbox').show();

                } else {
                    $('.countries-selectbox').hide();
                    $('.leagues-selectbox').hide();
                }
            });

            $('#send_to').trigger('change');

            $("#countries").select2();
            $("#selectAllCountries").click(function(){
                if($("#selectAllCountries").is(':checked') ){
                    $("#countries > option").prop("selected","selected");
                    $("#countries").trigger("change");

                } else {
                    $("#countries > option").prop("selected","");
                    $("#countries > option").removeAttr("selected");
                    $("#countries").trigger("change");
                }
            });
        });
    </script>
@endpush
