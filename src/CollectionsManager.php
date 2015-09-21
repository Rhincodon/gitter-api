<?php

namespace Rhinodontypicus\GitterApi;

class CollectionsManager
{
    /**
     * @param $className string
     * @param $items array
     * @param $gitterApi GitterApi
     * @param $extraData
     * @return \Illuminate\Support\Collection
     */
    public function getCollection($className, $items, $gitterApi, $extraData = null)
    {
        $result = collect([]);
        if (!$items) {
            return $result;
        }

        foreach ($items as $item) {
            $item = new $className($item, $gitterApi, $extraData);
            $result->push($item);
        }

        return $result;
    }
}
