<?php

// src/Service/MessageGenerator.php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class Omdbapi
{

    public function FilmAPI(string $NomFilm): array
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'http://www.omdbapi.com/?t='. $NomFilm .'&apikey=8d7a5ae9'
        );

        $content = $response->toArray();
        
        return $content;
    }
}