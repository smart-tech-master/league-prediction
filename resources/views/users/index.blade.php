@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header common_bg d-inline-flex justify-content-between">
                    <div>Users</div>
                    <div></div>
                </div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Profile Picture</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Dob</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @isset($users)
                            @foreach($users as $user)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $user->avatar }}" width="50"/>
                                    </td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ! is_null($user->dob) ? $user->dob->format('d M, Y') : null }}</td>
                                    <td>
                                        <a href="{{ route('users.show', [$user]) }}" class="btn btn-sm btn-info">Show</a>

{{--                                        <a class="btn btn-sm btn-danger"--}}
{{--                                           href="{{ route('messages.destroy', [$message]) }}"--}}
{{--                                           onclick="event.preventDefault();--}}
{{--                                           document.getElementById('delete-form-{{ $message->id }}').submit();"--}}
{{--                                        >{{ ! $message->trashed() ? 'Delete' : 'Restore' }}</a>--}}

{{--                                        <form id="delete-form-{{ $message->id }}"--}}
{{--                                              action="{{ route('messages.destroy', [$message]) }}"--}}
{{--                                              method="POST" style="display: none;">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                        </form>--}}
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

