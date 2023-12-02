@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-bullhorn"></i> Tutorial Screen
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm" action="{{route('ads.store', ['type' => request()->type])}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group file-upload">
                            <input type="file" name="file" id="file-upload"/>
                            <label for="file-upload">
                                <img src="img/upload-icon.svg" alt="upload icon"/>
                                <p>picture</p>
                                <span class="prediciton-btn">Upload a file</span>
                            </label>

                            @include('shared.errors.validation', ['name' => 'file'])
                        </div>

                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control-sm form-control"
                                   value="{{ old('title') }}"/>
                            @include('shared.errors.validation', ['name' => 'title'])
                        </div>
                    </div>

                    <div class="col-xl-6 d-flex flex-column">
                        <div class="form-group">
                            <label for="">Description</label>

                            <div class="selectbox no-bg">
                                <textarea id="description" name="description">{{ old('description') }}</textarea>
                                @include('shared.errors.validation', ['name' => 'description'])
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Sl</label>
                            <input type="text" name="sl" class="form-control-sm form-control"
                                   value="{{ old('sl') }}"/>
                            @include('shared.errors.validation', ['name' => 'sl'])
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
    <script src="js/tinymce.min.js"></script>
    <script src="js/jquery.tinymce.min.js"></script>
    <script>
        $('textarea#description').tinymce({});
    </script>
@endpush


