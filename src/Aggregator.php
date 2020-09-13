<?php

namespace Aggregators\Aws;

use Carbon\Carbon;
use InvalidArgumentException;
use Aggregators\Support\BaseAggregator;
use Symfony\Component\DomCrawler\Crawler;

class Aggregator extends BaseAggregator
{
    /**
     * {@inheritDoc}
     */
    public string $uri = 'https://aws.amazon.com/blogs/aws/';

    /**
     * {@inheritDoc}
     */
    public string $provider = 'Aws';

    /**
     * {@inheritDoc}
     */
    public string $logo = 'logo.png';

    /**
     * {@inheritDoc}
     */
    public function articleIdentifier(): string
    {
        return 'article.blog-post';
    }

    /**
     * {@inheritDoc}
     */
    public function nextUrl(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('div.blog-pagination > a.blog-btn-a:first-of-type')->first()->attr('href');
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function image(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('img.wp-post-image')->attr('src');
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function title(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('h2.blog-post-title')->text();
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function content(Crawler $crawler): ?string
    {
        try {
            return str_replace(' […]', '...', $crawler->filter('section.blog-post-excerpt > p')->text());
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function link(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('a.blog-read-more')->attr('href');
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dateCreated(Crawler $crawler): Carbon
    {
        try {
            return Carbon::parse($crawler->filter('time')->attr('datetime'));
        } catch (InvalidArgumentException $e) {
            return Carbon::now();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dateUpdated(Crawler $crawler): Carbon
    {
        return $this->dateCreated($crawler);
    }
}
