<?php

namespace Alura\CoursesSearcher\Tests;

use Alura\CoursesSearcher\Searcher;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class CoursesSearcherTest extends TestCase
{
    private $httpClientMock;
    private $url = 'url-test';

    protected function setUp(): void
    {
        $html = <<<HTML
        <html>
            <body>
                <span class="card-curso__nome">Curso 1</span>
                <span class="card-curso__nome">Curso 2</span>
                <span class="card-curso__nome">Curso 3</span>
            </body>
        </html>
        HTML;

        $stream = $this->createMock((StreamInterface::class));
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testSearchShouldReturnCourses(): void
    {
        $crawler = new Crawler();
        $searcher = new Searcher($this->httpClientMock, $crawler);
        $courses = $searcher->search($this->url);

        $this->assertCount(3, $courses);
        $this->assertEquals('Curso 1', $courses[0]);
        $this->assertEquals('Curso 2', $courses[1]);
        $this->assertEquals('Curso 3', $courses[2]);
    }
}
