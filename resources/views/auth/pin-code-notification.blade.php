@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form>
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12">

                                <label for="email" class="col-md-12 col-form-label" style="text-align: center" >
                                    please check your eamil, just sent pin code
                                </label>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
