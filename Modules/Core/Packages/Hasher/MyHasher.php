<?php

namespace Modules\Core\Packages\Hasher;

use Illuminate\Http\Request;
use Spatie\ResponseCache\Hasher\RequestHasher;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;

class MyHasher implements RequestHasher
{
    protected CacheProfile $cacheProfile;

    public function __construct(CacheProfile $cacheProfile)
    {
        $this->cacheProfile = $cacheProfile;
    }

    public function getHashFor(Request $request): string
    {
        $lang = locale();

        return 'responsecache-' . md5(
                "{$request->getHost()}-{$lang}-{$request->getRequestUri()}-{$request->getMethod()}/" . $this->cacheProfile->useCacheNameSuffix($request)
            );
    }
}
