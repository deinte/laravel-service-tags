<?php

namespace Deinte\LaravelServiceTags;

use Deinte\LaravelServiceTags\Service\ServiceTagger;

class LaravelServiceTags
{
    public function __construct(private readonly ServiceTagger $tagger)
    {
    }

    public function tagInterface(string $interface, string $tag): self
    {
        ($this->tagger)($interface, $tag);
        
        return $this;
    }
}
