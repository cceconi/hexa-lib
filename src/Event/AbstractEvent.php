<?php

namespace Apido\HexaLib\Event;

use Closure;
use DateTime;
use Apido\HexaLib\Exception\PermissionException;
use Apido\HexaLib\Message\FilterInterface;
use Apido\HexaLib\Message\IdentityFilter;
use Apido\HexaLib\Message\PayloadInterface;
use Apido\HexaLib\Message\ResultInterface;
use Apido\HexaLib\Operation\OperationWriterInterface;
use Apido\HexaLib\Presenter\IdentityPresenter;
use Apido\HexaLib\Presenter\PresenterInterface;
use Apido\HexaLib\Role\AbstractRole;
use Apido\HexaLib\User\DomainUser;
use Apido\HexaLib\User\DomainUserInterface;
use Apido\HexaLib\Utils\Uuidv4Interface;
use ReflectionClass;

abstract class AbstractEvent
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_DOMAIN_ERROR = 'error:domain';
    public const STATUS_INFRASTRUCTURE_ERROR = 'error:infrastructure';
    public const STATUS_TODO = 'todo';

    private Uuidv4Interface $uuidv4;
    private string $aggregateId;
    private string $localAggregateId;
    private DateTime $createdAt;
    private ReflectionClass $eventClass;
    protected DomainUser $user;
    private string $status;
    protected PayloadInterface $payload;
    protected ResultInterface $result;
    protected bool $isMaster;
    protected ?OperationWriterInterface $opsWriter;
    protected ?PresenterInterface $presenter;
    protected int $bestPermission = AbstractRole::FORBIDDEN;

    public function __construct(Uuidv4Interface $uuidv4, DomainUser $user, PayloadInterface $payload, ?OperationWriterInterface $opsWriter = null, ?string $aggregateRootId = null)
    {
        $this->uuidv4 = $uuidv4;
        $this->aggregateId = $aggregateRootId ?? $uuidv4->generate();
        $this->localAggregateId = $uuidv4->generate();
        $this->isMaster = !isset($aggregateRootId);
        $this->createdAt = new DateTime();
        $this->eventClass = new ReflectionClass($this);
        $this->user = $user;
        $this->status = self::STATUS_TODO;
        $this->payload = $payload;
        $this->opsWriter = $opsWriter;
    }

    private function findBestPermission(): void
    {
        $permissions = $this->getPermissions();
        foreach ($this->user->getRoles() as $role) {
            $permission = $permissions[$role->getRoleClass()] ?? AbstractRole::FORBIDDEN;
            $this->setBestPermission(AbstractRole::validatePermission($permission));
        }
    }

    private function setBestPermission(int $permission): void
    {
        $this->bestPermission = $this->bestPermission < $permission ? $permission : $this->bestPermission;
    }

    /**
     * @return static
     */
    public static function build(Uuidv4Interface $uuidv4, DomainUser $user, PayloadInterface $payload, ?OperationWriterInterface $opsWriter = null): self
    {
        return new static($uuidv4, $user, $payload, $opsWriter);
    }

    /**
     * @return static
     */
    public static function fromMainEvent(EventInterface $event, PayloadInterface $payload): self
    {
        return new static($event->getUuidV4(), $event->getUser(), $payload, $event->getOpsWriter(), $event->getAggregateId());
    }

    public function updateStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setResult(ResultInterface $result): void
    {
        $this->result = $result;
        $this->result->setFilter($this->getFilter());
        $this->result->setPresenter(new IdentityPresenter());
    }

    public function __toString(): string
    {
        return $this->aggregateId . ' - ' . $this->eventClass->getShortName() . ' - ' . $this->user . ' - ' . $this->status;
    }

    public function getMessage(string $message): string
    {
        return $this->aggregateId . ' - ' . $this->eventClass->getShortName() . ' - ' . $message;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'eventName' => $this->eventClass->getShortName(),
            'user' => $this->user->getFullname(),
            'status' => $this->status,
            'payload' => $this->payload,
        ];
    }

    protected function getFilters(): array
    {
        return [];
    }

    public function getFilter(): FilterInterface
    {
        $filters = $this->getFilters();
        $filter = null;
        foreach ($this->user->getRoles() as $role) {
            if (array_key_exists(get_class($role), $filters)) {
                $filter = $filters[get_class($role)];
            }
        }
        return $filter ? new $filter() : new IdentityFilter();
    }

    protected function getPermissions(): array
    {
        return [];
    }

    public function hasPermission(?Closure $complementaryPermission = null): void
    {
        $this->findBestPermission();
        if (!AbstractRole::hasPermission($this->bestPermission, $complementaryPermission)) {
            throw new PermissionException($complementaryPermission ? "See complementary grant" : "See permission role", 403);
        }
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getLocalAggregateId(): string
    {
        return $this->localAggregateId;
    }

    public function isMaster(): bool
    {
        return $this->isMaster;
    }

    public function getUser(): DomainUserInterface
    {
        return $this->user;
    }

    public function executeOperations(): void
    {
        if ($this->opsWriter) {
            $this->opsWriter->execute($this->aggregateId);
        }
    }

    public function setPresenter(PresenterInterface $presenter): void
    {
        $this->presenter = $presenter;
    }

    public function presentData(): void
    {
        if (isset($this->presenter)) {
            $this->result->setPresenter($this->presenter);
        }
    }

    public function getOpsWriter(): ?OperationWriterInterface
    {
        return $this->opsWriter;
    }

    public function getUuidv4(): Uuidv4Interface
    {
        return $this->uuidv4;
    }
}
