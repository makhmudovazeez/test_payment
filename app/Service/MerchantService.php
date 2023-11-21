<?php

namespace App\Service;

interface MerchantService
{
    public const APPLICATION_JSON = 'application/json';
    public const FORM_DATA = 'multipart/form-data';

    /**
     * @see \App\Service\MerchantServiceFacade::setContentType()
     *
     * @param string $type
     * @return self
     */
    public function setContentType(string $type): self;

    /**
     * @see \App\Service\MerchantServiceFacade::handle()
     *
     * @param array $data
     * @return mixed
     */
    public function handle(array $data);
}
