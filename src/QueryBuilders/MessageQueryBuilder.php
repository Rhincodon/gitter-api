<?php

namespace Rhinodontypicus\GitterApi\QueryBuilders;

use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\Models\Message;
use Rhinodontypicus\GitterApi\Models\Room;

class MessageQueryBuilder
{
    /**
     * @var GitterApi
     */
    private $gitterApi;

    /**
     * @var array
     */
    private $queries = [];

    /**
     * @var Room
     */
    private $room;

    /**
     * MessageQueryBuilder constructor.
     * @param $room Room
     * @param $gitterApi
     */
    public function __construct($room, $gitterApi)
    {
        $this->gitterApi = $gitterApi;
        $this->room = $room;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $query = $this->getResultQueryString();

        $response = $this->gitterApi->client->get("rooms/{$this->room->id}/chatMessages{$query}");
        $messages = json_decode($response->getBody()->getContents());
        $messagesCollection = $this->gitterApi->collectionsManager->getCollection(
            Message::class,
            $messages,
            $this->gitterApi
        );

        return $messagesCollection;
    }

    /**
     * @param $value integer
     * @return MessageQueryBuilder
     */
    public function skip($value)
    {
        return $this->addQuery(__METHOD__, $value);
    }

    /**
     * @param $value
     * @return MessageQueryBuilder
     */
    public function take($value)
    {
        return $this->addQuery("limit", $value);
    }

    /**
     * @param $message string|Message
     * @return MessageQueryBuilder
     */
    public function before($message)
    {
        if (is_string($message)) {
            return $this->addQuery("beforeId", $message);
        }

        return $this->addQuery("beforeId", $message->id);
    }

    /**
     * @param $message string|Message
     * @return MessageQueryBuilder
     */
    public function after($message)
    {
        if (is_string($message)) {
            return $this->addQuery("afterId", $message);
        }

        return $this->addQuery("afterId", $message->id);
    }

    /**
     * @return string
     */
    private function getResultQueryString()
    {
        $result = "";
        if (count($this->queries) <= 0) {
            return $result;
        }

        $result = "?";
        $this->queries = array_unique($this->queries);
        $result .= join("&", $this->queries);

        return $result;
    }

    /**
     * @param $name string
     * @param $value string|integer
     * @return string
     */
    private function getQueryString($name, $value)
    {
        return "{$name}={$value}";
    }

    /**
     * @param $method string
     * @param $value string|integer
     * @return MessageQueryBuilder
     */
    private function addQuery($method, $value)
    {
        $queryString = $this->getQueryString($method, $value);
        if (!in_array($queryString, $this->queries)) {
            array_push($this->queries, $queryString);

            return $this;
        }

        return $this;
    }
}
