@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex"><span><i class="fas fa-rocket"></i> Launch Screen Ad
</span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="UpdateForm" action="{{ route('ads.update', [$ad]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group file-upload">
                            <input type="file" id="file-upload" name="file"/>
                            <label for="file-upload">
                                <img src="img/upload-icon.svg" alt="upload icon"/>
                                <p>choose between the choices picture or Video or Gif Picture</p>
                                <span class="prediciton-btn">Upload a file</span>
                            </label>
                            @include('shared.errors.validation', ['name' => 'file'])
                        </div>

                        <div class="form-group">
                            <label for="">Link Type</label>

                            <div class="selectbox no-bg">
                                <select name="link_type" id="">
                                    <option value="">Select any</option>
                                    <option value="external" {{ (old('link_type') ?: $ad->link_type) == 'external' ? ' selected' : null }}>External</option>
                                    <option value="internal" {{ (old('link_type') ?: $ad->link_type) == 'internal' ? ' selected' : null }}>Internal</option>
                                </select>
                                @include('shared.errors.validation', ['name' => 'link_type'])
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Ad Link</label>

                            <div class="selectbox no-bg">
                                <input type="text" name="link" value="{{ old('link') ?: $ad->link }}">
                                @include('shared.errors.validation', ['name' => 'link'])
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn-area mt-5 text-right">
                            <button class="prediciton-btn">Launch</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>

    </div>

@endsection


