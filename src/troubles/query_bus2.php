<?php declare(strict_types = 1);

namespace C;

final class UserController
{
    /**
     * @return User[]
     */
    public function list(): array
    {
        $queryBus = new QueryBus([
            UsersHandler::class => new UsersHandler(),
            ProductsHandler::class => new ProductsHandler()
        ]);

        $query = new UsersQuery();
        $users = $queryBus->dispatch($query);

        $query2 = new ProductsQuery();
        $users2 = $queryBus->dispatch($query2);

        return $users2;
    }
}

final class QueryBus
{
    /**
     * @var array<string, QueryHandler>
     */
    private array $handlers;

    /**
     * ! phpstan: Method C\QueryBus::__construct() has parameter $handlers with generic interface C\QueryHandler but does not specify its types: T
     *
     * @param array<string, QueryHandler> $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @return mixed
     */
    public function dispatch(Query $query)
    {
        $handler = $this->handlers[get_class($query)];
        return $handler->handle($query);
    }
}

/**
 * @template T
 */
interface QueryHandler
{
    /**
     * @return T
     */
    public function handle(Query $query);
}

/**
 * @implements QueryHandler<array<User>>
 */
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

/**
 * @implements QueryHandler<array<Product>>
 */
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
