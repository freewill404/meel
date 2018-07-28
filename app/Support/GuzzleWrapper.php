<?php

namespace App\Support;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use LogicException;

class GuzzleWrapper
{
    protected $guzzle;

    protected $fake = false;

    /** @var $mockHandler MockHandler */
    protected $mockHandler;

    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function get($url)
    {
        return $this->guzzle->request('GET', $url);
    }

    public function fake()
    {
        if ($this->fake) {
            throw new LogicException('Guzzler is already being faked');
        }

        $this->fake = true;

        $handler = HandlerStack::create(
            $this->mockHandler = new MockHandler([])
        );

        $this->guzzle = new Guzzle(['handler' => $handler]);

        return $this;
    }

    public function pushResponse($response)
    {
        if (! $this->fake) {
            throw new LogicException('Guzzler is not being faked yet');
        }

        $this->mockHandler->append($response);

        return $this;
    }

    public function pushString($string, $status = 200, $headers = [])
    {
        return $this->pushResponse(
            new Response($status, $headers, $string)
        );
    }

    public function pushFile($filePath, $status = 200, $headers = [])
    {
        $string = file_get_contents($filePath);

        return $this->pushString($string, $status, $headers);
    }
}
