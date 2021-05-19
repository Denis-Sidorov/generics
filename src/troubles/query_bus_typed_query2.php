<?php declare(strict_types = 1);

namespace X;

final class UserController
{
    /**
     * @return User[]
     */
    public function list(): array
    {
        /** @var QueryBus<array<User>, Query, QueryHandler<Query>> $queryBus */
        $queryBus = new QueryBus([
            UsersHandler::class => new UsersHandler(),
            ProductsHandler::class => new ProductsHandler()
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
 * T2 и T3 нужны потому что нельзя написать QueryHandler<? of Query> в param и var
 *
 * @template T
 * @template T2 of Query
 * @template T3 of QueryHandler<T2>
 */
final class QueryBus
{
    /**
     * @var array<string, T3>
     */
    private array $handlers;

    /**
     * @param array<string, T3> $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param T2 $query
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
