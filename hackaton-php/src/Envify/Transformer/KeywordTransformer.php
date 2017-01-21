<?php

namespace Envify\Transformer;

class KeywordTransformer implements TransformerInterface
{
    private $matching = [];

    public function __construct($lang = 'en')
    {
        $filename = __DIR__ . '/../Dictionary/words.' . $lang . '.php';

        if (file_exists($filename)) {
            $this->matching = require $filename;
        }
    }

    public function transform(array $keywords)
    {
        return $this->getSearchKeywords($keywords);
    }

    /**
     * Transform an array of keywords from a picture to keywords for a search for hotel page
     *
     * @param array $keywords
     *
     * @return array
     */
    private function getSearchKeywords(array $keywords)
    {
        $searchCriteria = [];

        foreach ($keywords as $keyword) {
            if (array_key_exists($keyword, $this->matching)) {
                $searchCriteria[] = $this->matching[$keyword];
            } else {
                $searchCriteria[] = $keyword;
            }
        }

        return array_unique($searchCriteria);
    }
}