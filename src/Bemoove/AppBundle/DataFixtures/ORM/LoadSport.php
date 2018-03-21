<?php
// src/AppBundle/DataFixtures/ORM/LoadQuestionnaire.php
namespace Bemoove\AppBundle\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bemoove\AppBundle\Entity\Sport;

class LoadSport implements ORMFixtureInterface
{
    public function load(ObjectManager $em)
    {

    $sports = array("Accrobranche", "Aikido", "Alpinisme", "Aqua gym", "Athlétisme", "Badminton", "Baseball", "Basket ball", "Bodyboard", "Bowling", "Boxe américaine", "Boxe anglaise", "Boxe chinoise", "Boxe française", "Boxe thaïlandaise", "Canoë kayak", "Canyonisme", "Capoeira", "Char à voile", "Cirque", "Combat libre", "Course à pied", "Course d'orientation",
    "Cyclisme", "Deltaplane", "Equitation", "Escalade", "Escrime", "Fitness", "Flag", "Football", "Football US", "Futsal", "Golf", "Gymnastique douce", "Gymnastique rythmique", "Haltérophilie", "Handball", "Hockey subaquatique", "Hockey sur gazon", "Hockey sur glace", "Jetski", "Jiu-Jitsu brésilien", "Ju-Jitsu traditionnel", "Judo", "Karaté", "Karting", "Kendo",
    "Kick boxing", "Kin ball", "Kite surf", "Krav maga", "Kung fu", "Lutte", "Marche", "Moto", "Musculation", "Nage en eau vive", "Natation", "Paintball", "Parachutisme", "Parapente", "Pêche", "Pêche sous-marine", "Planche à voile", "Plongée", "Quad", "Rafting", "Raid nature", "Raquette à neige", "Roller", "Rugby", "Self défense",
    "Skateboard", "Ski", "Ski nautique", "Snowboard", "Softball", "Spéléologie", "Squash", "Surf", "Taekwondo", "Taï chi chuan", "Tennis", "Tennis de table", "Tir à l'arc", "Ultimate Frisbee", "Viet vo dao", "Voile", "Volley ball", "VTT", "Yoga");

    foreach ($sports as $sport) {
        $ObjSport = new Sport();
        $ObjSport->setName($sport);
        $ObjSport->setDescription("");
        $em->persist($ObjSport);
    }

    $em->flush();
    }
}
