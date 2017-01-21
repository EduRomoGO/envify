<?php

namespace Envify\Transformer;

class LocationTransformer implements TransformerInterface
{
    private $matching = [];

    public function __construct($country = 'spain')
    {
        $filename = __DIR__ . '/../Dictionary/locations.' . $country . '.php';

        if (file_exists($filename)) {
            $this->matching = require $filename;
        }
    }

    public function transform(array $keywords)
    {
        return $this->getLocationsByKeywords($keywords);
    }

    /**
     * Transform an array of [keywords => weight, ...] to an array of places,
     * the weight will be the sum of weights
     *
     * @param array $keywords
     *
     * @return array Locations
     */
    public function getLocationsByKeywords(array $keywords)
    {
        $locationsCriteria = [];

        foreach ($keywords as $keyword => $weight) {
            if (array_key_exists($keyword, $this->matching)) {
                $match = $this->matching[$keyword];

                foreach ($match as $location) {
                    if (array_key_exists($location, $locationsCriteria)) {
                        $locationsCriteria[$location] += $weight;
                    } else {
                        $locationsCriteria[$location] = $weight;
                    }
                }
            } else {
                // No location for this word, ignore
            }
        }

        return $locationsCriteria;
    }
}
