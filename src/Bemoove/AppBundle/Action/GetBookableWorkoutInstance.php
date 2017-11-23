<?php

// src/AppBundle/Action/GetBookableWorkoutInstance.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\WorkoutInstance;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetBookableWorkoutInstance
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
     *     name="getBookableWorkoutInstance",
     *     path="/getBookableWorkoutInstance",
     *     defaults={"_api_resource_class"=WorkoutInstance::class, "_api_collection_operation_name"="getBookableWorkoutInstance"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
    {
        $workout_id = false;
        if (isset($_GET["workout_id"]) && is_numeric($_GET['workout_id'])) {
            $workout_id = $_GET['workout_id'];
        }

        $WorkoutInstanceRepo = $this->em->getRepository('BemooveAppBundle:WorkoutInstance');
        $workoutInstances = $WorkoutInstanceRepo->findBookableByWorkout($workout_id);

        return $workoutInstances;
    }
}
