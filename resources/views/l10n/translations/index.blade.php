@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header common_bg d-inline-flex justify-content-between">
                    <div>Translations</div>
                    <div>
                        <a class="prediciton-btn" href="{{ route('l10n.translations.create') }}">
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
                                <th scope="col">Key</th>
                                <th scope="col">Text</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @isset($translations)
                            @foreach($translations as $translation)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $translation->key }}</td>
                                    <td>{{ $translation->getTranslation('text', 'ar') }}</td>
                                    <td>
                                        <a href="{{ route('l10n.translations.edit', [$translation]) }}"
                                           class="btn btn-sm btn-info">Edit</a>

                                        <a class="btn btn-sm btn-danger"
                                           href="{{ route('l10n.translations.destroy', [$translation]) }}"
                                           onclick="event.preventDefault();
                                           document.getElementById('delete-form-{{ $translation->id }}').submit();"
                                        >{{ ! $translation->trashed() ? 'Delete' : 'Restore' }}</a>

                                        <form id="delete-form-{{ $translation->id }}"
                                              action="{{ route('l10n.translations.destroy', [$translation]) }}"
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
@endsection

