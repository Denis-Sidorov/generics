<?php

declare(strict_types=1);

namespace B;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Setup;

/**
 * @extends ArrayCollection<int, User>
 */
final class UsersList extends ArrayCollection
{

}

final class User
{
    public string $id;
}

$users = new UsersList();
$users->add(new User());
$user = $users->current();

// phpstan: Cannot access property $id on B\User|false
echo $user->id;

// -------------------------------------

/**
 * @extends EntityRepository<User>
 */
final class UserRepository extends EntityRepository
{

}

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

$em = EntityManager::create($conn, $config);
$userRepository = $em->getRepository(User::class);
$user = $userRepository->find(1);
if (!$user) throw new \Exception('zxc');

// phpstan: Call to an undefined method B\User::getId()
echo $user->getId();
