<?php

namespace Tests\Units\Search;

use atoum;

class Search extends atoum
{

    public function testClass()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
            ->object($this->testedInstance)
            ->isTestedInstance();
    }

    public function testSerialized512()
    {
        $exceptedQuery = $this->makeElasticQuery();

        $serialized = serialize($exceptedQuery);
        $serialized512 = substr($serialized, 0, 512);

        $this->string($serialized512)
            ->isEqualTo(substr($serialized512, 0, -1) . 'a');

    }

    public function testSerialized513()
    {
        $exceptedQuery = $this->makeElasticQuery();

        $serialized = serialize($exceptedQuery);

        $this
            ->dump($serialized)
            ->string($serialized)
            ->isEqualTo(substr($serialized, 0, -1) . 'a');

    }

    private function makeElasticQuery()
    {
        $exceptedQuery = new \Elastica\Query();

        $aggregationFilters = new \Elastica\Aggregation\Filters("emotion");

        $negative = new \Elastica\Filter\Range('valeur', array('lt' => 0));
        $aggregationFilters->addFilter($negative, 'negative');

        $neutral = new \Elastica\Filter\Range('valeur', array('lt' => 1, 'gt' => -1));
        $aggregationFilters->addFilter($neutral, 'neutral');

        $positive = new \Elastica\Filter\Range('valeur', array('gt' => 0));
        $aggregationFilters->addFilter($positive, 'positive');

        $dateHisto = new \Elastica\Aggregation\DateHistogram('nb', 'date', 'month');
        $aggregationFilters->addAggregation($dateHisto);

        $exceptedQuery->addAggregation($aggregationFilters);

        return $exceptedQuery;
    }


}