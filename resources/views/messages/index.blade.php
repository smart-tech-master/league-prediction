@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header common_bg d-inline-flex justify-content-between align-items-center">
                        <div>Notifications</div>
                        <div>
                            <a class="prediciton-btn" href="{{ route('messages.create') }}">
                                <img src="img/plus.svg">
                                Create new
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Title</th>
                                <th scope="col">Text</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @isset($messages)
                                @foreach($messages as $message)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $message->title }}</td>
                                        <td>{{ $message->text }}</td>
                                        <td>
                                            <img src="{{ $message->picture }}" width="50"/>
                                        </td>
                                        <td>
                                            <a href="{{ route('messages.show', [$message]) }}" class="btn btn-sm btn-info">Show</a>

                                            <a class="btn btn-sm btn-danger"
                                               href="{{ route('messages.destroy', [$message]) }}"
                                               onclick="event.preventDefault();
                                               document.getElementById('delete-form-{{ $message->id }}').submit();"
                                            >{{ ! $message->trashed() ? 'Delete' : 'Restore' }}</a>

                                            <form id="delete-form-{{ $message->id }}"
                                                  action="{{ route('messages.destroy', [$message]) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

