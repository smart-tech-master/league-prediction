@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-language"></i> Translations
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm" action="{{route('l10n.translations.update', [$translation])}}" method="post">
                @csrf
                @method('put')

                <div class="row">

                    <div class="col-xl-6 d-flex flex-column">
                        <div class="form-group">
                            <label for="">Key</label>
                            <input type="text" name="key" class="form-control-sm form-control"
                                   value="{{ old('key') ?: $translation->key }}" readonly/>
                            @include('shared.errors.validation', ['name' => 'key'])
                        </div>

                        @isset($locales)
                            @foreach($locales as $locale)
                                <div class="form-group">
                                    <label for="">{{ $locale->name }}</label>
                                    <input type="text" name="text[{{ $locale->code }}]"
                                           class="form-control-sm form-control"
                                           value="{{ old('text.' . $locale->code) ?: $translation->getTranslation('text', $locale->code) }}"/>
                                    @include('shared.errors.validation', ['name' => 'text.' . $locale->code])
                                </div>
                            @endforeach
                        @endisset

                        <div class="btn-area">
                            <button type="submit" class="prediciton-btn">Add</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>

    </div>

@endsection


