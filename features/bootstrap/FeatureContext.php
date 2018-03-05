<?php

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\SchemaTool;
use Behat\Symfony2Extension\Context\KernelAwareContext;

use Behatch\Json\Json;
use Behatch\HttpCall\HttpCallResultPool;
use PHPUnit\Framework\Assert as Assertions;

use Bemoove\AppBundle\Entity\ForgottenPasswordToken;
/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var array
     */
    private $classes;

    /**
     * @var array
     */
    private $placeHolders = array();

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(HttpCallResultPool $httpCallResultPool, ManagerRegistry $doctrine, Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager $jwtManager)
    {
        $this->httpCallResultPool = $httpCallResultPool;
        $this->doctrine = $doctrine;
        $this->jwtManager = $jwtManager;
        $this->manager = $doctrine->getManager();
        $this->schemaTool = new SchemaTool($this->manager);
        $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();
    }


    /**
     * @BeforeScenario @createSchema
     */
    public function createDatabase()
    {
        $this->schemaTool->createSchema($this->classes);
    }
    /**
     * @AfterScenario @dropSchema
     */
    public function dropDatabase()
    {
        $this->schemaTool->dropSchema($this->classes);
    }

    public static function theDatabaseIsClean()
    {
        $this->schemaTool->dropSchema($this->classes);
        $this->createDatabase();
    }

    /**
     * @Given there are users:
     */
    public function thereAreUsersWithTheFollowingDetails(TableNode $users)
    {
        // foreach ($users->getColumnsHash() as $key => $val) {
        //
        //     $confirmationToken = isset($val['confirmation_token']) && $val['confirmation_token'] != ''
        //         ? $val['confirmation_token']
        //         : null;
        //
        //     $user = $this->userManager->createUser();
        //
        //     $user->setEnabled(true);
        //     $user->setUsername($val['username']);
        //     $user->setEmail($val['email']);
        //     $user->setPlainPassword($val['password']);
        //     $user->setConfirmationToken($confirmationToken);
        //
        //     if ( ! empty($confirmationToken)) {
        //         $user->setPasswordRequestedAt(new \DateTime('now'));
        //     }
        //
        //     $this->userManager->updateUser($user);
        // }
    }

    /**
     * @Then the payload should contain :arg1
     */
    public function thePayloadShouldContain($arg1)
    {
        //this doesn't work, i should learn a bit more on this topic
        $json = new Json($this->httpCallResultPool->getResult()->getValue());
        $token = $json->getContent()->token;
        $payload = $this->jwtManager->decode($json->getContent());
        var_dump($payload);
        throw new PendingException();
    }

    /**
     * @Then the response body contains JSON:
     */
    public function theResponseBodyContainsJson(PyStringNode $string)
    {
        // print $string;
        throw new PendingException();
    }

    /**
     * @Then the user :arg1 should have role :arg2
     */
    public function theUserShouldHaveRole($arg1, $arg2)
    {
        if(empty($arg2)) {
            Assertions::assertTrue(true);
            return;
        }
        $accountRepo = $this->manager->getRepository('BemooveAppBundle:Account');
        $account = $accountRepo->findOneByUsername($arg1);

        if ($account) {
            Assertions::assertTrue((in_array($arg2, $account->getRoles())), 'User '.$arg1.' does not have role '.$arg2);
            return;
        }

        throw new PendingException();
    }

    /**
     * @Given There is a forgotten password token :arg1 for email :arg2
     */
    public function thereIsAForgottenPasswordTokenForEmail($token, $email)
    {
        $accountRepo = $this->manager->getRepository('BemooveAppBundle:Account');
        $account = $accountRepo->findOneByUsername($email);

        if (!$account) {
            throw new \Exception('Account doesn\'t exists');
        }

        $forgottenPasswordToken = new ForgottenPasswordToken();
        $forgottenPasswordToken->setToken($token);
        $forgottenPasswordToken->setAccount($account);

        $this->manager->persist($forgottenPasswordToken);
        $this->manager->flush();
    }
}
