<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>

<div>
    <h4>Sort By:</h4>
    @isset($sortBy)
        @foreach(collect(explode(',', $sortBy)) as $filter)
            {{ \Illuminate\Support\Str::title($filter) }}
        @endforeach
    @endisset
    @if(isset($ageRange) && ! is_null($ageRange))
        {{ $ageRange }}
    @endif
    @if(isset($country) && ! is_null($country))
        Country({{ \App\Models\Country::find($country)->name ?? null }})
    @endif
</div>

<img src="{{ $userChart }}">

</body>
</html>
