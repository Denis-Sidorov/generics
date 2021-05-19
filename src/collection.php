<?php

declare(strict_types=1);

/**
 * @template T
 */
abstract class Collection
{
    /**
     * @var array<T>
     */
    private array $elements = [];

    /**
     * @return T|null
     */
    public function get(int $key)
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * @param T $element
     * @return $this
     */
    public function add($element): self
    {
        $this->elements[] = $element;

        return $this;
    }
}

final class User
{
    public string $name;
}

/**
 * @extends Collection<User>
 */
final class UserCollection extends Collection
{

}

$userList = new UserCollection();
$userList->add(new User());
$user = $userList->get(0);

// phpstan: Cannot access property $name on User|null
echo $user->name;
