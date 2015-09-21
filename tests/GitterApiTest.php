<?php

namespace Rhinodontypicus\GitterRestApi\Tests;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\Models\Room;
use Rhinodontypicus\GitterApi\Models\User;

class GitterRestApiTest extends \PHPUnit_Framework_TestCase
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
        try {
            $env->load();
        } catch (InvalidArgumentException $e) {
            echo "Env file not loaded";
        }

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
    public function it_fetches_all_rooms()
    {
        $rooms = $this->gitter->rooms();

        $this->assertTrue(get_class($rooms) == Collection::class);
    }

    /**
     * @test
     */
    public function in_fetches_single_room_by_room_object()
    {
        $rooms = $this->gitter->rooms();
        $room = $this->gitter->room($rooms->first());

        $this->assertTrue(get_class($room) == Room::class);
    }

    /**
     * @test
     */
    public function in_fetches_single_room_by_room_id()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");

        $this->assertTrue(get_class($room) == Room::class);
    }

    /**
     * @test
     */
    public function it_fetches_current_user()
    {
        $user = $this->gitter->currentUser();

        $this->assertTrue(get_class($user) == User::class);
    }
}
