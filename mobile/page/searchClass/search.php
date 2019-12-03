<?php
class Search{

    private $type1;
    private $type2;
    private $pdCate;
    private $pdCate2;
    private $priceFrom;
    private $priceTo;
    private $pdPriceType;
    private $stx;

    /**
     * @return mixed
     */
    public function getType1()
    {
        return $this->type1;
    }

    /**
     * @param mixed $type1
     */
    public function setType1($type1)
    {
        $this->type1 = $type1;
    }

    /**
     * @return mixed
     */
    public function getPdCate()
    {
        return $this->pdCate;
    }

    /**
     * @return mixed
     */
    public function getPdCate2()
    {
        return $this->pdCate2;
    }

    /**
     * @return mixed
     */
    public function getPdPriceType()
    {
        return $this->pdPriceType;
    }

    /**
     * @return mixed
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @return mixed
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @return mixed
     */
    public function getStx()
    {
        return $this->stx;
    }

    /**
     * @return mixed
     */
    public function getType2()
    {
        return $this->type2;
    }

    /**
     * @param mixed $pdCate
     */
    public function setPdCate($pdCate)
    {
        $this->pdCate = $pdCate;
    }

    /**
     * @param mixed $pdCate2
     */
    public function setPdCate2($pdCate2)
    {
        $this->pdCate2 = $pdCate2;
    }

    /**
     * @param mixed $pdPriceType
     */
    public function setPdPriceType($pdPriceType)
    {
        $this->pdPriceType = $pdPriceType;
    }

    /**
     * @param mixed $priceFrom
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;
    }

    /**
     * @param mixed $priceTo
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;
    }

    /**
     * @param mixed $stx
     */
    public function setStx($stx)
    {
        $this->stx = $stx;
    }

    /**
     * @param mixed $type2
     */
    public function setType2($type2)
    {
        $this->type2 = $type2;
    }
}

$searchClass = new Search();
?>