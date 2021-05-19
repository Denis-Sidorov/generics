<?php declare(strict_types = 1);

namespace Z;

final class UserController
{
    /**
     * @return User[]
     */
    public function list(): array
    {
        // phpstan: Parameter #1 $handlers of class Z\QueryBus constructor expects array<string, Z\QueryHandler<Z\Query>>, array(Z\UsersHandler, Z\ProductsHandler) given.
        $queryBus = new QueryBus([
            new UsersHandler(),
            new ProductsHandler()
        ]);

        $query = new UsersQuery();
        $users = $queryBus->dispatch($query);

        $query2 = new ProductsQuery();
        $users2 = $queryBus->dispatch($query2);

        // Должна быть ошика, т.к. $user2 -> Product[]
        return $users2;
    }
}

/**
 * @template T
 */
final class QueryBus
{
    /**
     * @var array<string, QueryHandler<Query>>
     */
    private array $handlers;

    /**
     * @param array<string, QueryHandler<Query>> $handlers
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

/**
 * @template T of Query
 */
interface QueryHandler
{
    /**
     * @param T $query
     * @return mixed
     */
    public function handle($query);
}

/**
 * @implements QueryHandler<UsersQuery>
 */
final class UsersHandler implements QueryHandler
{
    /**
     * Have to specify param for IDE
     *
     * @param UsersQuery $query
     * @return User[]
     */
    public function handle($query): array
    {
        return [new User()];
    }
}

/**
 * @implements QueryHandler<ProductsQuery>
 */
final class ProductsHandler implements QueryHandler
{
    /**
     * Have to specify param for IDE
     *
     * @param ProductsQuery $query
     * @return Product[]
     */
    public function handle($query): array
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
