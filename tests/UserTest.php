<?php

namespace Rhinodontypicus\GitterRestApi\Tests;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;
use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\Models\Message;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GitterApi
     */
    protected $gitter;

    /**
     * SetUp
     */
    public function setUp()
    {
        $env = new Dotenv(realpath(__DIR__ . '/../'));
        $env->load();
        $this->gitter = new GitterApi(getenv('GITTER_TOKEN'));
    }

    /**
     * @test
     */
    public function token_is_setup()
    {
        $this->assertEquals($this->gitter->getToken(), getenv('GITTER_TOKEN'));
    }

    /**
     * @test
     */
    public function it_fetches_user_rooms()
    {
        $currentUser = $this->gitter->currentUser();
        $userRooms = $currentUser->rooms();

        $this->assertTrue(get_class($userRooms) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_user_channels()
    {
        $currentUser = $this->gitter->currentUser();
        $userRooms = $currentUser->channels();

        $this->assertTrue(get_class($userRooms) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_user_organizations()
    {
        $currentUser = $this->gitter->currentUser();
        $userOrgs = $currentUser->organizations();

        $this->assertTrue(get_class($userOrgs) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_user_repos()
    {
        $currentUser = $this->gitter->currentUser();
        $userRepos = $currentUser->repositories();

        $this->assertTrue(get_class($userRepos) == Collection::class);
    }
}
