<?php declare(strict_types = 1);

namespace V;

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

        // phpstan: Method V\UserController::list() should return array<V\User> but returns array<V\Product>
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
     * @param array<string, QueryHandler> $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @template R
     * @param Query<R> $query
     * @return R
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
     * @template R
     * @param Query<R> $query
     * @return R
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

/**
 * @template R
 */
interface Query
{
}

/**
 * @implements Query<array<User>>
 */
final class UsersQuery implements Query
{
}

/**
 * @implements Query<array<Product>>
 */
final class ProductsQuery implements Query
{
}

final class User {}

final class Product {}
