<?php

// src/AppBundle/Action/GetMyCurrentCart.php

namespace OrderBundle\Action;

use Bemoove\AppBundle\Entity\Account;
use OrderBundle\Entity\Cart;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

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
      // le cart courant est:
      // -- lié par l'IP
      // -- potentiellement lié par le membre (Person)
      // -- est recent d'au moins 30 minutes

      // n'est pas :
      // -- utilisé par une commande en cours
      // -- abandonné volontairement
      $account = $this->securityTokenStorage->getToken()->getUser();
      $person = null;
      if ($account instanceOf Account) {
        $person = $account->getPerson();
      }

      $request = Request::createFromGlobals();
      $originIp = $request->getClientIp();

      $CartRepo = $this->em->getRepository('OrderBundle:Cart');
      $cart = $CartRepo->findCurrentCart($originIp, $person);

      $orderRepo = $this->em->getRepository('OrderBundle:Order');
      $order = $orderRepo->findOneByCart($cart);
      if($order === null) {
        return $cart;
      }

      return 'no current cart';

  }

}
