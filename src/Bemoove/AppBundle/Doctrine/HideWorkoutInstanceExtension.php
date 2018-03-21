<?php
// api/src/Doctrine/HideWorkoutInstanceExtension.php

namespace Bemoove\AppBundle\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;

use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Workout;
use Bemoove\AppBundle\Entity\WorkoutInstance;

use Doctrine\ORM\QueryBuilder;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class HideWorkoutInstanceExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $securityTokenStorage;
    private $authorizationChecker;

    public function __construct(TokenStorageInterface $securityTokenStorage, AuthorizationCheckerInterface $checker)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->authorizationChecker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $resourceClass
     */
    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        // Don't know why $resourceClass can be the resource on cart Post
        if (WorkoutInstance::class === $resourceClass) {
            $allAlias = $queryBuilder->getAllAliases();
            $businessAlias = preg_grep('/^business.*/', $allAlias);
            sort($businessAlias);
            if(count($businessAlias) < 1) {
              return;
            }
            $ofValidBusiness = sprintf('%s.isValid = true', $businessAlias[0]);

            //si pas log, ne peut voir que des valid
            if (!$token = $this->securityTokenStorage->getToken()) {
                throw new \Exception("No anonymous token find", 1);
                return null;
            }

            $account = $token->getUser();
            if ($account instanceof Account && $this->authorizationChecker->isGranted('ROLE_PARTNER')) {
                $business = $account->getBusiness();
                $queryBuilder->andWhere($ofValidBusiness.' OR '.sprintf('%s.id = :businessid', $businessAlias[0]));
                $queryBuilder->setParameter('businessid', $business->getId());
            } else {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $notSoldOutBusiness = sprintf('%s.soldOut = 0', $rootAlias);
                $queryBuilder->andWhere($ofValidBusiness);
                $queryBuilder->andWhere($notSoldOutBusiness);
            }
        }
    }
}
