<?php

namespace Bemoove\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SearchController extends Controller
{
    public function resultAction(Request $request)
    {
        $search_string = $request->request->get('training_search')['search'];
        $search_array = $this->analyseSearch($search_string);

        $max_pertinence = 0;
        $em = $this->getDoctrine()->getManager();
        $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');

        $training = array();
        if(count($search_array["date"]) > 0) {
            $max_pertinence++;
        }

        if(count($search_array["sport"]) > 0) {
            $max_pertinence++;
            $res_sport = $repo_trainingSession->findBy( array('sport' => $search_array["sport"]));
            $training[] = $res_sport;
        }

        if(count($search_array["city"]) > 0) {
            $max_pertinence++;
            $res_city = $repo_trainingSession->getByCityId($search_array["city"]);
            $training[] = $res_city;
        }

        if(count($search_array["tag"]) > 0) {
            $max_pertinence++;
        }

        if(count($search_array["unknow"]) > 0) {
            $max_pertinence++;
        }

        $sorted_training = array();
        $this->sortByNbOccurence($sorted_training,$training,$max_pertinence);

        krsort($sorted_training);

        $twig['sorted_training'] = $sorted_training;

        return $this->render('BemooveAppBundle:Search:result.html.twig',$twig);
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');
        $trainingSessionList = $repo_trainingSession->findAll();

        $twig['trainingSessionList'] = $trainingSessionList;

        foreach($trainingSessionList as &$trainginsession) {
            $trainginsession->setNbTicketBooked(rand(0,4));
        }

        dump($trainingSessionList);

        return $this->render('BemooveAppBundle:Search:list.html.twig',$twig);
    }

    public function viewtrainingAction($idtrainingsession) {
        $em = $this->getDoctrine()->getManager();
        $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');
        $trainingSession = $repo_trainingSession->find($idtrainingsession);

        $twig['trainingSession'] = $trainingSession;

        return $this->render('BemooveAppBundle:Search:trainingsession.html.twig',$twig);
    }

    private function analyseSearch($search_string) {
        $em = $this->getDoctrine()->getManager();
        $cleaned_search = array("date" => array(),"sport" => array(),"city" => array(),"tag" => array(), "unknow" => array());
        $search_array = explode(" ",$search_string);

        //Essaye de localiser les villes, les extrait et les supprimes de la chaine
        $repo_City = $em->getRepository('Bemoove\AppBundle\Entity\Place\City');
        $city_list = $repo_City->findAllAsArray();
        $city_searched = array_keys(array_intersect($city_list,$search_array));
        $search_array = array_diff($search_array,$city_list);

        $cleaned_search['city'] = $city_searched;

        //Essaye de localiser les dates, les extrait et les supprimes de la chaine

        //Essaye de localiser les sports, les extrait et les supprimes de la chaine
        $repo_Sport = $em->getRepository('Bemoove\AppBundle\Entity\Sport');
        $sport_list = $repo_Sport->findAllAsArray();
        $sport_searched = array_keys(array_intersect($sport_list,$search_array));
        $search_array = array_diff($search_array,$sport_list);

        $cleaned_search['sport'] = $sport_searched;
        //Essaye de localiser les tags, les extrait et les supprimes de la chaine



        return $cleaned_search;
    }

    private function sortByNbOccurence(&$sorted_training,$training,$max_pertinence) {
        $upped_training = array();
        if(count($training) == 0) { return;}
        if(count($sorted_training) == 0) {
            $sorted_training = array_fill(0,$max_pertinence,array());
            $sorted_training[0] = array_merge($sorted_training[0],$training[0]);
        }
        else {
            foreach($sorted_training as $key => $sortedtrainingsession) {
                if(count($sortedtrainingsession) == 0) { continue;}
                $better = array_uintersect($sortedtrainingsession, $training[0],
                    function ($obj_a, $obj_b) {
                        $is_same_training = !($obj_a->getId() == $obj_b->getId());
                        return $is_same_training;
                    });

                //Ce sort corrige le tir, je ne comprends pas
                ksort($better);
                ksort($sortedtrainingsession);
                $upped_training[$key+1] = $better;

                $not_improved = array_udiff($sortedtrainingsession, $better ,
                    function ($obj_a, $obj_b) {
                        $is_same_training = !($obj_a->getid() === $obj_b->getid());
                        return $is_same_training;
                    });

                $new_basic = array_udiff($training[0], $sortedtrainingsession,
                    function ($obj_a, $obj_b) {
                        $is_same_training = !($obj_a->getid() === $obj_b->getid());
                        return $is_same_training;
                    });

                $sorted_training[$key] = array_merge($not_improved,$new_basic);
            }

            foreach($sorted_training as $key => &$tab) {
                if(isset($upped_training[$key])) {
                    $tab = array_merge($tab,$upped_training[$key]);
                }
            }
        }
        unset($training[0]);
        sort($training);
        if (count($training) > 0) {
            $this->sortByNbOccurence($sorted_training, $training,$max_pertinence);
        }
        /*
        if (count($Subset) <= 0) {
            $Subset = array_chunk($set_list[0], 1);
        } else {
            foreach ($Subset as $item) {
                foreach ($set_list[0] as $additem) {
                    $tmp_subset[] = array_merge($item, array($additem));
                }
            }
            $Subset = $tmp_subset;
        }
        unset($set_list[0]);
        sort($set_list);
        if (count($set_list) > 0) {
            $this->subsetEnumeration($Subset, $set_list);
        }*/
    }
}
