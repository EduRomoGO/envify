<?php

namespace Envify\Transformer;

use Envify\Service\HotelBedsService;
use Envify\Service\MinubeService;
use Envify\Service\RatioService;

class LocationTransformer implements TransformerInterface
{
    /** @var MinubeService */
    private $minubeService;

    /** @var HotelBedsService  */
    private $hotelBedsService;

    /** @var RatioService  */
    private $ratioService;

    public function __construct(
        MinubeService $minubeService,
        HotelBedsService $hotelBedsService,
        RatioService $ratioService
    ) {
        $this->minubeService = $minubeService;
        $this->hotelBedsService = $hotelBedsService;
        $this->ratioService = $ratioService;
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

        $locationWeights = $this->ratioService->getLocationWeight();
        $locationCodes = $this->minubeService->getZones();

        foreach ($keywords as $keyword => $weight) {
            $category = $this->minubeService->getCategoryIdByName($keyword);

            if (!property_exists($category, 'id')) {
                continue;
            }

            $pois = $this->minubeService->getPoisByCategoryId($category->id);

            foreach ($pois as $poi) {
                // The weight here changes to be the value from the ecologic ratios
                if (array_key_exists($poi->zone_id, $locationCodes)) {
                    $province = $locationCodes[$poi->zone_id];
                    $lowerName = strtolower(str_replace(' ', '_', $province));

                    if (array_key_exists($lowerName, $locationWeights)) {
                        $weight = $locationWeights[$lowerName];
                    }
                }

                if (array_key_exists($poi->id, $resultPois)) {
                    $poi->weight += $weight;
                } else {
                    $poi->weight = $weight;
                }

                $location = new \stdClass();
                $location->lat = $poi->latitude;
                $location->lng = $poi->longitude;

                $resultPois[] = [
                    'id' => $poi->id,
                    'name' => $poi->name,
                    'location' => $location,
                    'weight' => $poi->weight,
                    // 'hotels' => $this->hotelBedsService->getHotelsByCoords($location->lat, $location->lng, 5)
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
