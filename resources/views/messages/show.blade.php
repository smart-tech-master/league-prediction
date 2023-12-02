@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-envelope"></i> Notifications
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Title</label>
                            {{ $message->title }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    @if(! is_null($message->text))
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="">Text</label>
                                {{ $message->text }}
                            </div>
                        </div>
                    @endif

                    @if(! is_null($message->picture))
                        <div class="col-xl-6">
                            <div class="form-group file-upload">
                                <img src="{{ $message->picture }}" alt="{{ $message->id }}" width="100"/>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Send To</label>

                                {{ ucfirst($message->data->send_to) }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Countries</label>

                            @isset($countries)
                                {{ $countries->pluck('name')->implode(',') }}
                            @endisset
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Leagues</label>
                            @isset($leagues)
                                {{ $leagues->pluck('name')->implode(',') }}
                            @endisset
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection


