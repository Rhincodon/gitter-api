<?php

namespace Rhinodontypicus\GitterApi;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Rhinodontypicus\GitterApi\Models\Room;
use Rhinodontypicus\GitterApi\Models\User;

class GitterApi
{
    /**
     * @var Client
     */
    public $client;
    /**
     * @var string
     */
    private $token;

    /**
     * @var CollectionsManager
     */
    public $collectionsManager;

    /**
     * GitterRestApi constructor.
     * @param $token string
     */
    public function __construct($token)
    {
        $this->setToken($token);
        $this->collectionsManager = new CollectionsManager();
    }

    /**
     * Setting auth token
     * @param $token string
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->setClient();
    }

    /**
     * Getting auth token
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Getting list of rooms
     * @return Collection
     */
    public function rooms()
    {
        $response = $this->client->get("rooms");
        $rooms = json_decode($response->getBody()->getContents());
        $roomsCollection = $this->collectionsManager->getCollection(Room::class, $rooms, $this);

        return $roomsCollection;
    }

    /**
     * @param $room string|Room
     * @return Room
     */
    public function room($room)
    {
        if (is_string($room)) {
            $roomUrl = $this->getRoomUri($room);
        } else {
            $roomUrl = $room->uri;
        }

        $response = $this->client->post("rooms", [
            "json" => [
                "uri" => $roomUrl
            ]
        ]);

        return new Room(json_decode($response->getBody()->getContents()), $this);
    }

    /**
     * @return User
     */
    public function currentUser()
    {
        $response = $this->client->get("user");

        return new User(json_decode($response->getBody()->getContents())[0], $this);
    }

    /**
     * Setting HTTP Client
     */
    private function setClient()
    {
        $this->client = new Client([
            "base_uri" => "https://api.gitter.im/v1/",
            "headers"  => [
                "Content-Type"  => "application/json",
                "Accept"        => "application/json",
                "Authorization" => "Bearer {$this->token}"
            ]
        ]);
    }

    /**
     * @param $roomId
     * @return mixed
     */
    private function getRoomUri($roomId)
    {
        $allRooms = $this->rooms();

        $index = $allRooms->search(function ($room) use ($roomId) {
            return $room->id == $roomId;
        });

        return $allRooms[$index]->uri;
    }
}
