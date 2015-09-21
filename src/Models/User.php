<?php

namespace Rhinodontypicus\GitterApi\Models;

use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\ReflectionsContainer;

class User
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $displayName;
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $avatarUrlSmall;
    /**
     * @var string
     */
    public $avatarUrlMedium;

    /**
     * @var GitterApi
     */
    public $gitterApi;

    /**
     * User constructor.
     * @param $properties array
     * @param $gitterApi GitterApi
     */
    public function __construct($properties, $gitterApi)
    {
        $this->gitterApi = $gitterApi;
        $existsProperties = ReflectionsContainer::getExistsProperties(User::class);

        foreach ($properties as $key => $property) {
            if ($key == 'screenName') {
                $key = 'displayName';
            }
            if ($key == 'userId') {
                $key = 'id';
            }
            if (in_array($key, $existsProperties)) {
                $this->{$key} = $property;
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function rooms()
    {
        $response = $this->gitterApi->client->get("user/{$this->id}/rooms");
        $rooms = json_decode($response->getBody()->getContents());
        $roomsCollection = $this->gitterApi->collectionsManager->getCollection(Room::class, $rooms, $this->gitterApi);

        return $roomsCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function channels()
    {
        $response = $this->gitterApi->client->get("user/{$this->id}/channels");
        $rooms = json_decode($response->getBody()->getContents());
        $roomsCollection = $this->gitterApi->collectionsManager->getCollection(Room::class, $rooms, $this->gitterApi);

        return $roomsCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function organizations()
    {
        $response = $this->gitterApi->client->get("user/{$this->id}/orgs");
        $orgs = json_decode($response->getBody()->getContents());
        $orgsCollection = $this->gitterApi->collectionsManager->getCollection(
            Organization::class,
            $orgs,
            $this->gitterApi
        );

        return $orgsCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function repositories()
    {
        $response = $this->gitterApi->client->get("user/{$this->id}/repos");
        $repos = json_decode($response->getBody()->getContents());
        $reposCollection = $this->gitterApi->collectionsManager->getCollection(
            Repository::class,
            $repos,
            $this->gitterApi
        );

        return $reposCollection;
    }
}
