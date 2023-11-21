<?php

namespace App\Service;

use App\Exceptions\MediaTypeException;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MerchantServiceFacade implements MerchantService
{
    protected string $contentType = self::APPLICATION_JSON;

    public function __construct(protected array $merchantConfig)
    {
    }

    public function setContentType(string $type): self
    {
        $this->contentType = $type;
        return $this;
    }

    /**
     * @param array $data
     * @param string|null $sign
     * @return string
     * @throws MediaTypeException
     */
    public function handle(array $data): string
    {
        ksort($data);

        if ($this->contentType === self::APPLICATION_JSON) {
            $resp = $this->handleJson($data);
        } else {
            $resp = $this->handleFormData($data);
        }

        $ttl = Carbon::now()->addDay()->setTime(0, 0)->timestamp - Carbon::now()->timestamp;
        Cache::put($this->contentType, true, (int)$ttl);

        return $resp;
    }

    protected function handleJson(array $data): string
    {
        unset($data['sign']);
        return hash('sha256', implode(':', $data) . $this->merchantConfig['key']);
    }

    protected function handleFormData(array $data): string
    {
        unset($data['header']);
        return md5(implode('.', $data) . $this->merchantConfig['key']);
    }
}
