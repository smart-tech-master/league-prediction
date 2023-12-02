@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex"><span><i class="fas fa-{{ $page->icon }}"></i> {{ $page->universal_title }}
</span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="UpdateForm" action="{{ route('pages.update', [$page]) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-6">
                        @if(in_array($page->type, ['contact-us', 'about-us']))
                            <div class="form-group file-upload">
                                <input type="file" id="file-upload" name="picture"/>
                                <label for="file-upload">
                                    <img src="img/upload-icon.svg" alt="upload icon"/>
                                    <p>choose the picture</p>
                                    <span class="prediciton-btn">Upload a file</span>
                                </label>
                                @include('shared.errors.validation', ['name' => 'picture'])
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">Content</label>

                            <div class="selectbox no-bg">
                                <textarea id="content" name="content">{{ old('content') ?: $page->content }}</textarea>
                                @include('shared.errors.validation', ['name' => 'content'])
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn-area mt-5 text-right">
                            <button class="prediciton-btn">Update</button>
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
        $('textarea#content').tinymce({});
    </script>
@endpush
