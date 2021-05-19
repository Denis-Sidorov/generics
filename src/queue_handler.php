<?php declare(strict_types = 1);

/**
 * @template T of Message
 */
interface QueueHandler
{
    /**
     * @param T $message
     */
    public function handle(Message $message): void;
}

/**
 * @implements QueueHandler<SyncMessage>
 */
final class SyncHandler implements QueueHandler
{
    /**
     * @param SyncMessage $message Needs for IDE
     */
    public function handle(Message $message): void
    {
        $id = $message->getId();
    }
}

interface Message
{
    public function getId(): string;
}

final class SyncMessage implements Message
{
    public function getId(): string
    {
        return uniqid('sync-', true);
    }

    public function getHash(): string
    {
        return md5($this->getId());
    }
}

final class HelloMessage implements Message
{
    public function getId(): string
    {
        return uniqid('hello-', true);
    }
}

final class Consumer
{
    private SyncHandler $handler;

    public function __construct(SyncHandler $handler)
    {
        $this->handler = $handler;
    }

    public function listen(): void
    {
        // get data from queue and deserialize it to message

        $handler = new SyncHandler();

        $message = new SyncMessage();
        $this->handler->handle($message);

        // error
        $message = new HelloMessage();
        $this->handler->handle($message);
    }
}
