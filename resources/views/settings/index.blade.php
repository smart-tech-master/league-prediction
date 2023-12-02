@extends('layouts.app')

@section('content')
    <div class="general-settings">
        <h5 class="page-title d-flex">
            <span><i class="fa fa-cog"></i> General Settings </span>
        </h5>

        <div class="settings-wrapper">

            <form method="post" action="{{ route('settings.store') }}"  onsubmit="alert('The setting updates might take a long time, please be patient.');">
                @csrf

                <div class="single-settings">
                    <div class="settings-title">Reminder Notification</div>
                    <div class="settings-control">
                        <div class="checkbox-style">
                            <label for="notificaiton">
                                <input type="checkbox" id="notificaiton" name="notifications[reminder]" {{ (old('notifications.reminder') ?: ($settings->firstWhere('type', 'notifications.reminder')->content ?? null)) ? ' checked' : null }}>
                                <span class="check-box"></span>
                            </label>
                            @include('shared.errors.validation', ['name' => 'notifications.reminder'])
                        </div>
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Reminder Notification Body Text</div>
                    <div class="settings-control">
                        <div class="">
                            <label for="">
                                <textarea name="body_text[reminder]">{{ (old('body_text.reminder') ?: ($settings->firstWhere('type', 'body_text.reminder')->content ?? null)) }}</textarea>
                            </label>
                            @include('shared.errors.validation', ['name' => 'body_text.reminder'])
                        </div>
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">End of Round Notification</div>
                    <div class="settings-control">
                        <div class="checkbox-style">
                            <label for="notificaiton-round">
                                <input type="checkbox" id="notificaiton-round" name="notifications[end_of_round]" {{ (old('notifications.end_of_round') ?: ($settings->firstWhere('type', 'notifications.end_of_round')->content ?? null)) ? ' checked' : null }}>
                                <span class="check-box"></span>
                            </label>
                            @include('shared.errors.validation', ['name' => 'notifications.end_of_round'])
                        </div>
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">End of Round Notification Body Text</div>
                    <div class="settings-control">
                        <div class="">
                            <label for="">
                                <textarea name="body_text[end_of_round]">{{ (old('body_text.end_of_round') ?: ($settings->firstWhere('type', 'body_text.end_of_round')->content ?? null)) }}</textarea>
                            </label>
                            @include('shared.errors.validation', ['name' => 'body_text.end_of_round'])
                        </div>
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Profile picture size limit</div>
                    <div class="settings-control">
                        <input type="number" name="sizes[profile_picture_max]" value="{{ old('sizes.profile_picture_max') ?: ($settings->firstWhere('type', 'sizes.profile_picture_max')->content ?? null) }}"/>
                        @include('shared.errors.validation', ['name' => 'sizes.profile_picture_max'])
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Season</div>
                    <div class="settings-control">
                        <select name="season">
                            @for($year = 2022 ; $year <= 2030; $year++)
                            <option {{($year == $season->year)?'selected="selected"':''}}>{{$year}}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="single-settings">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>League Name</td>
                            <td>Display in App</td>
                            <td>Appearance Order</td>

                        </tr>

                        @isset($leagues)
                            @foreach($leagues as $league)
                                <tr>
                                    <td>{{ $league->name }}</td>
                                    <td>
                                        <div class="checkbox-style">
                                            <label for="display-app-{{ $league->id }}">
                                                <input type="checkbox" id="display-app-{{ $league->id }}"
                                                       name="leagues[{{ $league->id }}][display_in_app]"  {{ (old('leagues.' . $league->id . '.display_in_app') ?: ($settings->firstWhere('type', 'leagues.' . $league->id . '.display_in_app')->content ?? null)) ? ' checked' : null }}>
                                                <span class="check-box"></span>
                                            </label>
                                            @include('shared.errors.validation', ['name' => 'leagues.' . $league->id . '.display_in_app'])
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox-style">
                                            <input type="number" name="leagues[{{ $league->id }}][appearance_order]" value="{{ old('leagues.' . $league->id . '.appearance_order') ?: ($settings->firstWhere('type', 'leagues.' . $league->id . '.appearance_order')->content ?? null) }}"/>
                                            @include('shared.errors.validation', ['name' => 'leagues.' . $league->id . '.appearance_order'])
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset

                        </tbody>
                    </table>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Email</div>
                    <div class="settings-control">
                        <input type="text" name="contact[email]" value="{{ old('contact.email') ?: ($settings->firstWhere('type', 'contact.email')->content ?? null) }}"/>
                        @include('shared.errors.validation', ['name' => 'contact.email'])
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Facebook</div>
                    <div class="settings-control">
                        <input type="text" name="contact[facebook]" value="{{ old('contact.facebook') ?: ($settings->firstWhere('type', 'contact.facebook')->content ?? null) }}"/>
                        @include('shared.errors.validation', ['name' => 'contact.facebook'])
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Twitter</div>
                    <div class="settings-control">
                        <input type="text" name="contact[twitter]" value="{{ old('contact.twitter') ?: ($settings->firstWhere('type', 'contact.twitter')->content ?? null) }}"/>
                        @include('shared.errors.validation', ['name' => 'contact.twitter'])
                    </div>
                </div>

                <div class="single-settings">
                    <div class="settings-title">Instagram</div>
                    <div class="settings-control">
                        <input type="text" name="contact[instagram]" value="{{ old('contact.instagram') ?: ($settings->firstWhere('type', 'contact.instagram')->content ?? null) }}"/>
                        @include('shared.errors.validation', ['name' => 'contact.instagram'])
                    </div>
                </div>

                <div class="btn-area">
                    <button type="submit" class="prediciton-btn">Update</button>
                </div>
            </form>
        </div>

    </div>

@endsection

