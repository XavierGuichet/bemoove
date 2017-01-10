<?php
// src/AppBundle/DataFixtures/ORM/LoadQuestionnaire.php
namespace OC\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Questionnaire;


class LoadCity implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    // Liste des choix
    /*$Questionnaires[] = array( "Titre"	  => "Rubrique 1",
							  "Questions" => array( array("Chocolat","Barbe à papa"),
													array("Baignade","Promenade"),
													array("Sauvignon","Saint-emilion"),
													array("Metro","Velo"),
													array("Chanter","Danser")
													)
							);*/

    /*foreach ($Questionnaires as $Questionnaire) {
		$ObjQuestionnaire = new Questionnaire();
		$ObjQuestionnaire->setTitre($Questionnaire["Titre"]);

		foreach($Questionnaire["Questions"] as $key => $Question) {
			$ObjQuestionnaireQuestion = new QuestionnaireQuestion();
			$ObjQuestionnaireQuestion->setOrdre($key);
			$ObjQuestion = new Question();

			$ObjQuestionnaireQuestion->setQuestionnaire($ObjQuestionnaire);
			$ObjQuestionnaireQuestion->setQuestion($ObjQuestion);

			foreach($Question as $choix) {
				$objChoix = new Choix();
				$objChoix->setImagepath(slug_it($choix));
				$objChoix->setTitre($choix);

				$manager->persist($objChoix);
				$ObjQuestion->addChoix($objChoix);
			}

			$manager->persist($ObjQuestionnaireQuestion);
			$manager->persist($ObjQuestion);
		}

		$manager->persist($ObjQuestionnaire);

    }

    $manager->flush();*/
  }

}
