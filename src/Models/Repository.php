<?php

namespace Rhinodontypicus\GitterApi\Models;

use Rhinodontypicus\GitterApi\GitterApi;
use Rhinodontypicus\GitterApi\ReflectionsContainer;
use stdClass;

class Repository
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $uri;
    /**
     * @var boolean
     */
    public $private;
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
     */
    public function __construct($properties, $gitterApi)
    {
        $this->gitterApi = $gitterApi;
        $existsProperties = ReflectionsContainer::getExistsProperties(Repository::class);

        foreach ($properties as $key => $property) {
            if ($key == 'room') {
                $this->room = new Room($property, $gitterApi);
                continue;
            }

            if (in_array($key, $existsProperties)) {
                $this->{$key} = $property;
            }
        }
    }
}
