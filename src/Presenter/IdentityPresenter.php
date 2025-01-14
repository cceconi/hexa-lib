<?php

namespace Apido\HexaLib\Presenter;

class IdentityPresenter implements PresenterInterface
{
    public function present(array $data): array
    {
        return $data;
    }
}