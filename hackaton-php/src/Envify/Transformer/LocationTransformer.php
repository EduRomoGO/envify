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
        $this->getLocationsByKeywords($keywords);
    }

    /**
     * Transform an array of keywords from a picture to keywords for a search for hotel page
     *
     * @param array $keywords
     *
     * @return array
     */
    public function getLocationsByKeywords(array $keywords)
    {
        $locationsCriteria = [];

        foreach ($keywords as $keyword) {
            if (array_key_exists($keyword, $this->matching)) {
                $locationsCriteria[] = $this->matching[$keyword];
            } else {
                $locationsCriteria[] = $keyword;
            }
        }

        return $locationsCriteria;
    }
}
