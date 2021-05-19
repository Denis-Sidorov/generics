<?php declare(strict_types = 1);

final class UserController
{
    /** @var QueryBus<array<User>> */
    private QueryBus $queryBus;

    public function __construct()
    {
        $this->queryBus = new QueryBus([
            UsersHandler::class => new UsersHandler(),
            ProductsHandler::class => new ProductsHandler()
        ]);
    }

    /**
     * @return User[]
     */
    public function list(): array
    {
        $query = new UsersQuery();
        $users = $this->queryBus->dispatch($query);

        $query2 = new ProductsQuery();
        $users2 = $this->queryBus->dispatch($query2);

        // Должна быть ошибка, т.к. $users2 -> Product[]
        return $users2;
    }
}

/**
 * @template T
 */
final class QueryBus
{
    /**
     * @var array<string, QueryHandler>
     */
    private array $handlers;

    /**
     * @param array<string, QueryHandler> $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @return T
     */
    public function dispatch(Query $query)
    {
        $handler = $this->handlers[get_class($query)];
        return $handler->handle($query);
    }
}

interface QueryHandler
{
    /**
     * @return mixed
     */
    public function handle(Query $query);
}

final class UsersHandler implements QueryHandler
{
    /**
     * Have to specify param for IDE
     *
     * @param UsersQuery $query
     * @return User[]
     */
    public function handle(Query $query): array
    {
        return [new User()];
    }
}

final class ProductsHandler implements QueryHandler
{
    /**
     * Have to specify param for IDE
     *
     * @param ProductsQuery $query
     * @return Product[]
     */
    public function handle(Query $query): array
    {
        return [new Product()];
    }
}

interface Query
{
}

final class UsersQuery implements Query
{
}

final class ProductsQuery implements Query
{
}

final class User {}

final class Product {}
