<?php

namespace App\Jobs\Feeds;

use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Jobs\BaseJob;
use App\Meel\Feeds\FeedItems;
use App\Models\Feed;
use App\Support\Facades\Guzzler;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use Zend\Feed\Exception\RuntimeException as FeedException;

class PollFeedJob extends BaseJob implements ShouldQueue
{
    public $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function handle()
    {
        $this->feed->user->emails_left
            ? $this->pollFeed()
            : FeedNotPolled::dispatch($this->feed);
    }

    protected function pollFeed()
    {
        try {
            /** @var Response $response */
            $response = Guzzler::get($this->feed->url);

            $feedItems = new FeedItems(
                $response->getBody()->getContents()
            );

            FeedPolled::dispatch($this->feed, $feedItems);
        } catch (RequestException $requestException) {
            $message = $requestException->hasResponse()
                ? 'meel.feed-http-error-'.$requestException->getResponse()->getStatusCode()
                : $requestException->getMessage();

            $this->pollFailed($message);
        } catch (FeedException $exception) {
            $this->pollFailed('meel.feed-parse-error');
        } catch (Exception $exception) {
            $this->pollFailed($exception->getMessage());
        }
    }

    protected function pollFailed($message)
    {
        FeedPollFailed::dispatch($this->feed, $message);
    }
}
