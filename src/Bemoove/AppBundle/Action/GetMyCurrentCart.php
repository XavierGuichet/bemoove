<?php

// src/AppBundle/Action/GetMyCurrentCart.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Cart;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyCurrentCart
{
  private $securityTokenStorage;
  private $em;

  public function __construct(TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em)
  {
    $this->securityTokenStorage = $securityTokenStorage;
    $this->em = $em;
  }

  /**
   * @Route(
   *     name="getMyCurrentCart",
   *     path="/getMyCurrentCart",
   *     defaults={"_api_resource_class"=Cart::class, "_api_collection_operation_name"="getMyCurrentCart"}
   * )
   * @Method("GET")
   */
  public function __invoke($data)
  {
      dump('invoked');
      // return new Cart();
      $account = $this->securityTokenStorage->getToken()->getUser();

      $CartRepo = $this->em->getRepository('BemooveAppBundle:Cart');

      $person = $account->getPerson();

      $cart = $CartRepo->findOneBy(
                      array(
                        'member' => $person),
                      array(
                        'dateAdd' => 'DESC'
                      ));
      dump($cart);

      return $cart;
  }

}
