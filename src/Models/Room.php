<?php

namespace Rhinodontypicus\GitterApi\Models;

use Rhinodontypicus\GitterApi\QueryBuilders\MessageQueryBuilder;
use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\ReflectionsContainer;
use stdClass;

class Room
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $topic;
    /**
     * @var string
     */
    public $uri;
    /**
     * @var boolean
     */
    public $oneToOne;
    /**
     * @var User
     */
    public $user;
    /**
     * @var integer
     */
    public $userCount;
    /**
     * @var integer
     */
    public $unreadItems;
    /**
     * @var integer
     */
    public $mentions;
    /**
     * @var string
     */
    public $lastAccessTime;
    /**
     * @var false
     */
    public $lurk;
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $githubType;
    /**
     * @var integer
     */
    public $v;

    /**
     * @var GitterApi
     */
    public $gitterApi;

    /**
     * Room constructor.
     * @param $properties stdClass
     * @param $gitterApi GitterApi
     */
    public function __construct($properties, $gitterApi)
    {
        $this->gitterApi = $gitterApi;
        $existsProperties = ReflectionsContainer::getExistsProperties(Room::class);

        foreach ($properties as $key => $property) {
            if ($key == 'user') {
                $this->user = new User($property, $gitterApi);
                continue;
            }

            if (in_array($key, $existsProperties)) {
                $this->{$key} = $property;
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function users()
    {
        $response = $this->gitterApi->client->get("rooms/{$this->id}/users");
        $users = json_decode($response->getBody()->getContents());
        $usersCollection = $this->gitterApi->collectionsManager->getCollection(User::class, $users, $this->gitterApi);

        return $usersCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function channels()
    {
        $response = $this->gitterApi->client->get("rooms/{$this->id}/channels");
        $rooms = json_decode($response->getBody()->getContents());
        $roomsCollection = $this->gitterApi->collectionsManager->getCollection(Room::class, $rooms, $this->gitterApi);

        return $roomsCollection;
    }

    /**
     * @return MessageQueryBuilder
     */
    public function messages()
    {
        return new MessageQueryBuilder($this, $this->gitterApi);
    }

    /**
     * @param $text
     * @return Message
     */
    public function sendMessage($text)
    {
        $response = $this->gitterApi->client->post("rooms/{$this->id}/chatMessages", [
            "json" => [
                "text" => $text
            ]
        ]);

        return new Message(json_decode($response->getBody()->getContents()), $this->gitterApi, $this);
    }
}
