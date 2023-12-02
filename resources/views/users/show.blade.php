@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-users"></i> Users
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Full Name</label>
                            {{ $user->full_name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Username</label>
                                {{ $user->username }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Email</label>{{ $user->email }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Date of Birth</label>
                            {{ ! is_null($user->dob) ? $user->dob->format('d M, Y') : null }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Profile Picture</label>
                            <img src="{{ $user->profile_picture }}" width="50"/>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Device(Token+Platform)</label>
                            {{ $user->device_token }} + {{ $user->device_platform }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Provider(Service+ID)</label>
                            {{ $user->provider }} + {{ $user->provider_id }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Locale</label>
                            {{ $user->locale->name }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Received Notifications</label>
                            {{ $user->received_notifications }}
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Country</label>
                            {{ $user->country->name }}
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection


