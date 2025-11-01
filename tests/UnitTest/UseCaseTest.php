<?php

namespace Apido\Tests\HexaLib\UnitTest;

use Apido\HexaLib\Exception\PermissionException;
use Apido\HexaLib\Exception\MissingComplementaryClosureException;
use Apido\HexaLib\User\DomainUser;
use Apido\Tests\HexaLib\Domain\Shared\Role\AdminRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\MachineRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCasePayload;
use Apido\Tests\HexaLib\Infrastructure\Utils\Uuidv4;
use Apido\Tests\HexaLib\Domain\Shared\Role\UserRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryExceptionEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryForbiddenEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyComplexUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryPermissionUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionPayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyComplexUseCasePayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\TryExceptionQuery;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\TryForbiddenQuery;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\TryMyCommand;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\TryMyComplexCommand;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\TryPermissionQuery;
use Apido\Tests\HexaLib\Infrastructure\Presenter\ComplexUseCasePresenter;
use Apido\Tests\HexaLib\Infrastructure\Repository\StatementWriter;
use Apido\Tests\HexaLib\Infrastructure\Service\MyService;
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
class UseCaseTest extends TestCase
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
    
    public function testBasicUseCase(): void
    {
        $useCase = new TryMyCommand($this->myService, $this->logger);
        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole(), new AdminRole()], "Mixed Test"), new TryMyUseCasePayload(), new StatementWriter());
        $useCase->apply($event);
        $data = $event->getResult()->getData();

        $this->assertInstanceOf(MyModel::class, $data);
        $this->assertEquals("🎉 Success!!!", $data->getValue());
        $this->assertEquals("something", $data->getSomeValue());
    }
    
    public function testInfrastructureExceptionUseCase(): void
    {
        $useCase = new TryMyCommand($this->myService, $this->logger);
        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryMyUseCasePayload(true), new StatementWriter());
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Service Exception");

        $useCase->apply($event);
    }

    public function testExceptionUseCaseWithPermissionException(): void
    {
        $useCase = new TryExceptionQuery($this->logger, $this->myService);
        $event = TryExceptionEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryExceptionPayload(
            $this->uuidv4->generate()
        ));
        
        $this->expectException(PermissionException::class);

        $useCase->apply($event);
    }

    public function testForbiddenUseCase(): void
    {
        $useCase = new TryForbiddenQuery($this->myService, $this->logger);
        $event = TryForbiddenEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryMyUseCasePayload());
        
        $this->expectException(PermissionException::class);

        $useCase->apply($event);
    }

    public function testBasicUseCaseWithComplementaryPermissionException(): void
    {
        $useCase = new TryMyCommand($this->myService, $this->logger);
        $event = TryMyUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new MachineRole()], "Machine Test"), new TryMyUseCasePayload());
        
        $this->expectException(MissingComplementaryClosureException::class);

        $useCase->apply($event);
    }
    
    public function testPermissionUseCase(): void
    {
        $useCase = new TryPermissionQuery($this->myService, $this->logger);
        $event = TryPermissionUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryMyUseCasePayload());
        $useCase->apply($event);
        $data = $event->getResult()->getData();

        $this->assertInstanceOf(MyModel::class, $data);
        $this->assertEquals("🎉 Success!!!", $data->getValue());
        $this->assertEquals("something", $data->getSomeValue());
    }
    
    public function testPermissionUseCaseWithPermissionException(): void
    {
        $useCase = new TryPermissionQuery($this->myService, $this->logger);
        $event = TryPermissionUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new MachineRole()], "Machine Test"), new TryMyUseCasePayload());
        
        $this->expectException(PermissionException::class);
        $useCase->apply($event);
    }
    
    public function testComplexUseCase(): void
    {
        $useCase = new TryMyComplexCommand(new TryMyCommand($this->myService, $this->logger), $this->logger);
        $event = TryMyComplexUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new AdminRole()], "Admin Test"), new TryMyComplexUseCasePayload(new TryMyUseCasePayload()));
        $useCase->apply($event);
        $data = $event->getResult()->getData();

        $this->assertInstanceOf(MyModel::class, $data);
        $this->assertEquals("🎉 Complex success!!!", $data->getValue());
        $this->assertEquals("something", $data->getSomeValue());

        $result = json_decode(json_encode($event->getResult()), true);

        $this->assertArrayHasKey("adminValue", $result);
    }
    
    public function testComplexAdminDataUseCase(): void
    {
        $useCase = new TryMyComplexCommand(new TryMyCommand($this->myService, $this->logger), $this->logger);
        $event = TryMyComplexUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new UserRole()], "User Test"), new TryMyComplexUseCasePayload(new TryMyUseCasePayload()));
        $useCase->apply($event);
        $result = json_decode(json_encode($event->getResult()), true);

        $this->assertArrayNotHasKey("adminValue", $result);
    }
    
    public function testComplexPresenterDataUseCase(): void
    {
        $useCase = new TryMyComplexCommand(new TryMyCommand($this->myService, $this->logger), $this->logger);
        $event = TryMyComplexUseCaseEvent::build($this->uuidv4, new DomainUser($this->uuidv4->generate(), [new AdminRole()], "Admin Test"), new TryMyComplexUseCasePayload(new TryMyUseCasePayload()));
        $event->setPresenter(new ComplexUseCasePresenter());
        $useCase->apply($event);
        $result = json_decode(json_encode($event->getResult()), true);

        $this->assertArrayNotHasKey("adminValue", $result);
        $this->assertArrayHasKey("admin_value", $result);
    }
}
