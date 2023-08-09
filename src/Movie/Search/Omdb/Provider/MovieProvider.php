<?php

namespace App\Movie\Search\Omdb\Provider;

use App\Entity\Movie;
use App\Movie\Search\SearchTypes;

class MovieProvider
{
    public function getMovie(SearchTypes $type, string $value): Movie
    {
        // 1. Fetch data from OMDb
        // 2. Use the data to check if we have the movie in database
        // 2.1. If yes, return the movie
        // 3. if not build the movie object
        // 4. save movie in database
        // 5. return the movie
    }
}
