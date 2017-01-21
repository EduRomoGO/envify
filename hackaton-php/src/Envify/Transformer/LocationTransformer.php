<?php

namespace Envify\Transformer;

use Envify\Service\MinubeService;

class LocationTransformer implements TransformerInterface
{
    /** @var MinubeService */
    private $minubeService;

    public function __construct(MinubeService $minubeService)
    {
        $this->minubeService = $minubeService;
    }

    /**
     * Transform an array of [keywords => weight, ...] to an array of places,
     * the weight will be the sum of weights
     *
     * @param array $keywords
     *
     * @return array Locations
     */
    public function transform(array $keywords)
    {
        $resultPois = [];
        foreach ($keywords as $keyword => $weight) {
            $category = $this->minubeService->getCategoryIdByName($keyword);
            // echo 'Searching ' . $keyword . '...';

            if (!property_exists($category, 'id')) {
                //echo ' NOT FOUND';
                continue;
            }

            // echo 'CATID: ' . $category->id . '<br />'   ;

            $pois = $this->minubeService->getPoisByCategoryId($category->id);

            foreach ($pois as $poi) {
                if (array_key_exists($poi->id, $resultPois)) {
                    $poi->weight += $weight;
                } else {
                    $poi->weight = $weight;
                }

                $location = new \stdClass();
                $location->lat = $poi->latitude;
                $location->lng = $poi->longitude;

                $resultPois[$poi->id] = [
                    'name' => $poi->name,
                    'location' => $location,
                    'weight' => $poi->weight,
                    'hotels' => []
                ];
            }
        }

        /*
         * return [
         *      'City/Poi' => [
         *          'name' => 'Name of place',
         *          'location' => ['lat' => ..., 'lng' => ...],
         *          'weight' => (int) Weight
         *       ],
         *      ...
         * ]
         */
        return $resultPois;
    }
}
