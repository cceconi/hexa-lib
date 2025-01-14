<?php

namespace Apido\Tests\HexaLib\Infrastructure\Presenter;

use Apido\HexaLib\Presenter\PresenterInterface;

class ComplexUseCasePresenter implements PresenterInterface
{
    public function present(array $myModel): array
    {
        if (array_key_exists("adminValue", $myModel)) {
            $myModel["admin_value"] = $myModel["adminValue"];
            unset($myModel["adminValue"]);
        }
        return $myModel;
    }
}
