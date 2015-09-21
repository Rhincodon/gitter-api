<?php

namespace Rhinodontypicus\GitterRestApi\Tests;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\Models\Message;

class RoomTest extends \PHPUnit_Framework_TestCase
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
    public function it_fetches_room_users()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $roomUsers = $room->users();

        $this->assertTrue(get_class($roomUsers) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_room_channels()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $roomChannels = $room->channels();

        $this->assertTrue(get_class($roomChannels) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_room_messages()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $roomMessages = $room->messages()->get();

        $this->assertTrue(get_class($roomMessages) == Collection::class);
    }

    /**
     * @test
     */
    public function it_fetches_room_messages_with_filters()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $roomMessages = $room->messages()->skip(10)->take(10)->get();

        $this->assertTrue(get_class($roomMessages) == Collection::class);
        $this->assertTrue($roomMessages->count() == 10);
    }

    /**
     * @test
     */
    public function it_fetches_room_messages_with_before_filter()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $beforeMessage = $room->messages()->before("55ff5aba3a8116ed5f697d07")->take(1)->get();
        $afterMessage = $room->messages()->after($beforeMessage->first())->take(1)->get();

        $this->assertTrue(get_class($beforeMessage) == Collection::class);
        $this->assertTrue($beforeMessage->count() == 1);
        $this->assertTrue($afterMessage->first()->id == "55ff5aba3a8116ed5f697d07");
    }

    /**
     * @test
     */
    public function it_sent_message_to_the_room()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $message = $room->sendMessage("Test");

        $this->assertTrue(get_class($message) == Message::class);
        $this->assertTrue($message->text == "Test");
    }

    /**
     * @test
     */
    public function it_update_message()
    {
        $room = $this->gitter->room("54dbbd6b15522ed4b3dbe7a7");
        $message = $room->sendMessage("Test");
        $message = $message->update("TestUpdated");

        $this->assertTrue(get_class($message) == Message::class);
        $this->assertTrue($message->text == "TestUpdated");
    }
}
