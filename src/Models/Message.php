<?php

namespace Rhinodontypicus\GitterApi\Models;

use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\ReflectionsContainer;
use stdClass;

class Message
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $text;
    /**
     * @var string
     */
    public $html;
    /**
     * @var string
     */
    public $sent;
    /**
     * @var string|null
     */
    public $editedAt;
    /**
     * @var boolean
     */
    public $unread;
    /**
     * @var integer
     */
    public $readBy;
    /**
     * @var array
     */
    public $urls;
    /**
     * @var stdClass[]
     */
    public $issues;
    /**
     * @var stdClass
     */
    public $meta;
    /**
     * @var integer
     */
    public $v;
    /**
     * @var User
     */
    public $fromUser;
    /**
     * @var \Illuminate\Support\Collection
     */
    public $mentions;
    /**
     * @var GitterApi
     */
    public $gitterApi;

    /**
     * @var Room
     */
    public $room;

    /**
     * Room constructor.
     * @param $properties stdClass
     * @param $gitterApi GitterApi
     * @param $room Room
     */
    public function __construct($properties, $gitterApi, $room)
    {
        $this->gitterApi = $gitterApi;
        $this->room = $room;
        $existsProperties = ReflectionsContainer::getExistsProperties(Message::class);

        foreach ($properties as $key => $property) {
            if ($key == 'fromUser') {
                $this->fromUser = new User($property, $gitterApi);
                continue;
            }

            if ($key == 'mentions' && count($property) > 0) {
                $this->mentions = collect([]);
                foreach ($property as $item) {
                    $this->mentions->push(new User($item, $gitterApi));
                }
                continue;
            }

            if (in_array($key, $existsProperties)) {
                $this->{$key} = $property;
            }
        }
    }

    /**
     * @return User
     */
    public function author()
    {
        return $this->fromUser;
    }

    /**
     * @param $text
     * @return Message
     */
    public function update($text)
    {
        $response = $this->gitterApi->client->put("rooms/{$this->room->id}/chatMessages/{$this->id}", [
            "json" => [
                "text" => $text
            ]
        ]);

        return new Message(json_decode($response->getBody()->getContents()), $this->gitterApi, $this->room);
    }
}
