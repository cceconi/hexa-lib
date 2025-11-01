<?php declare(strict_types=1);

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model;

use Apido\HexaLib\Model\AbstractEntity;
use Apido\HexaLib\Model\Type\AggregateRootInterface;
use Apido\Tests\HexaLib\Domain\Shared\ValueObject\Uid;

class BusinessEntity extends AbstractEntity implements AggregateRootInterface
{
    private Status $status;
    private AdminInfo $adminInfo;
    private ProcessedData $processedData;

    public function __construct(string $uid, string $status, string $adminInfo, string $processedData)
    {
        parent::__construct(new Uid($uid));
        $this->status = new Status($status);
        $this->adminInfo = new AdminInfo($adminInfo);
        $this->processedData = new ProcessedData($processedData);
        $this->validate();
    }

    public function validate(): void
    {
        // Additional validation logic can be added here if needed
    }

    public function updateStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getAdminInfo(): AdminInfo
    {
        return $this->adminInfo;
    }

    public function getProcessedData(): ProcessedData
    {
        return $this->processedData;
    }

    public function toArray(): array
    {
        return [
            'uid' => (string)$this->getUid(),
            'status' => (string)$this->status,
            'adminInfo' => (string)$this->adminInfo,
            'processedData' => (string)$this->processedData,
        ];
    }
}