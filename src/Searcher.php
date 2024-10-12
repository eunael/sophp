<?php

namespace Alura\CoursesSearcher;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Searcher
{
    public function __construct(
        private ClientInterface $httpClient,
        private Crawler $crawler
    ) {
    }

    public function search(string $url): array
    {
        $response = $this->httpClient->request('GET', $url);

        $html = $response->getBody();

        $this->crawler->addHtmlContent($html);

        $courses = $this->crawler->filter('span.card-curso__nome');

        $courseList = [];

        foreach ($courses as $course) {
            $courseList[] = $course->textContent;
        }

        return $courseList;
    }
}
