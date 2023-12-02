<?php

namespace App\Services\ApiFootball;

use Illuminate\Support\Facades\Http;

class ApiFootballService
{
    protected $key;
    protected $host;
    protected $http;
    protected $fixtureFinishedStates = ['FT', 'AET', 'PEN'];
    protected $fixtureTimestampStates = ['TBD', 'SUSP', 'INT', 'PST', 'CANC', 'ABD', 'AWD', 'WO'];

    public function __construct($key, $host)
    {
        $this->key = $key;
        $this->host = $host;
        $this->http = Http::withHeaders([
            'x-rapidapi-key' => $key,
            'x-rapidapi-host' => $host,
            'content-type' => 'application/json'
        ])->baseUrl('https://' . $host . '/v3/')->withoutVerifying();
    }

    public function leagues($params = [])
    {
        return $this->http->get('leagues', $params);
    }

    public function rounds($params = [])
    {
        return $this->http->get('fixtures/rounds', $params);
    }

    public function fixtures($params = [])
    {
        return $this->http->get('fixtures', $params);
    }

    public function teams($params = [])
    {
        return $this->http->get('teams', $params);
    }

    public function venues($params = [])
    {
        return $this->http->get('venues', $params);
    }

    public function predictions($params = []) {
        return $this->http->get('predictions', $params);
    }

    public function countries()
    {
        return $this->http->get('teams/countries');
    }

    public function standings($params = [])
    {
        return $this->http->get('standings', $params);
    }

    public function fixtureFinishedStates(){
        return $this->fixtureFinishedStates;
    }

    public function fixtureTimestampStates(){
        return $this->fixtureTimestampStates;
    }
}
