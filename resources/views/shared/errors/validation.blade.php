@if ($errors->has($name))

    <div class="col-form-label text-danger">
        <ul>
            @foreach($errors->get($name) as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>

@endif
