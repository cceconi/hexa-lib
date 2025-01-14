<?php

namespace Apido\Tests\HexaLib\UnitTest;

use Apido\HexaLib\Exception\MissingComplementaryClosureException;
use Apido\HexaLib\Message\IdentityFilter;
use Apido\HexaLib\User\DomainUser;
use Apido\Tests\HexaLib\Domain\Shared\Role\UserRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCasePayload;
use Apido\Tests\HexaLib\Infrastructure\Service\MyService;
use Apido\Tests\HexaLib\Infrastructure\Utils\Uuidv4;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryUsageProcessor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Apido\HexaLib\UseCase\AbstractUseCase
 * @covers \Apido\HexaLib\Event\AbstractEvent
 * @covers \Apido\HexaLib\Handler\EventHandler
 * @covers \Apido\HexaLib\Role\AbstractRole
 * @covers \Apido\HexaLib\User\DomainUser
 * @covers \Apido\HexaLib\Message\AbstractResult
 * @covers \Apido\HexaLib\Message\IdentityFilter
 * @covers \Apido\HexaLib\Presenter\IdentityPresenter
 */
class LibraryTest extends TestCase
{
    private Logger $logger;
    private Uuidv4 $uuidv4;
    private MyService $myService;
    
    public function setUp(): void
    {        
        $this->logger = new Logger('unit test');
        $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->pushProcessor(new MemoryUsageProcessor());
        $this->uuidv4 = new Uuidv4();
        $this->myService = new MyService();
    }
    
    public function testEventData(): void
    {
        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryMyUseCasePayload());
        $eventData = $event->toArray();
        $this->assertArrayHasKey("aggregateId", $eventData);
        $this->assertArrayHasKey("eventName", $eventData);
        $this->assertEquals("TryMyUseCaseEvent", $eventData["eventName"]);
    }
    
    public function testEventNoRole(): void
    {
        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [], "User Test"), new TryMyUseCasePayload());
        $eventData = $event->toArray();
        $this->assertArrayHasKey("aggregateId", $eventData);
        $this->assertArrayHasKey("eventName", $eventData);
        $this->assertEquals("TryMyUseCaseEvent", $eventData["eventName"]);
    }
    
    public function testEventBadRole(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new IdentityFilter()], "User Test"), new TryMyUseCasePayload());
        $eventData = $event->toArray();
        $this->assertArrayHasKey("aggregateId", $eventData);
        $this->assertArrayHasKey("eventName", $eventData);
        $this->assertEquals("TryMyUseCaseEvent", $eventData["eventName"]);
    }
    
    public function testUser(): void
    {
        $user = new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test");

        $this->assertEquals("User Test", $user->getFullname());
        $this->assertEquals(36, mb_strlen($user->getUid()));
    }
}
