@extends('layouts.app')

@section('content')

    <div class="report-wrapper">
        <h5 class="page-title d-flex">
                    <span>
                    <i class="fas fa-envelope"></i> Contact
                    </span>
        </h5>

        <div class="ads-banner-wrapper">
            <form id="submitForm">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Name</label>
                            {{ $contact->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    @if(! is_null($contact->email))
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="">Email</label>
                                {{ $contact->email }}
                            </div>
                        </div>
                    @endif

                    @if(! is_null($contact->phone))
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="">Phone</label>
                                {{ $contact->phone }}
                            </div>
                        </div>
                    @endif

                </div>

                <div class="row">

                    <div class="col-xl-6">
                        <div class="form-group">
                            <label for="">Message</label>
                            {{ $contact->message }}
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection


