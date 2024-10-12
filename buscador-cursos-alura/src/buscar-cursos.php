<?php

require 'vendor/autoload.php';

use Alura\CoursesSearcher\Searcher;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);

$searcher = new Searcher($client, new Crawler());
$courses = $searcher->search('cursos-online-programacao/php');

foreach($courses as $course) {
    displayCourse($course);
}
