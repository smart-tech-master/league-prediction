@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header common_bg d-inline-flex justify-content-between align-items-center">
                        <div>Contacts</div>
                        <div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
{{--                                <th scope="col">Phone</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @isset($contacts)
                                @foreach($contacts as $contact)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>
                                            <a href="{{ route('contacts.show', [$contact]) }}" class="btn btn-sm btn-info">Show</a>

                                            <a class="btn btn-sm btn-danger"
                                               href="{{ route('contacts.destroy', [$contact]) }}"
                                               onclick="event.preventDefault();
                                               document.getElementById('delete-form-{{ $contact->id }}').submit();"
                                            >{{ ! $contact->trashed() ? 'Delete' : 'Restore' }}</a>

                                            <form id="delete-form-{{ $contact->id }}"
                                                  action="{{ route('contacts.destroy', [$contact]) }}"
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

