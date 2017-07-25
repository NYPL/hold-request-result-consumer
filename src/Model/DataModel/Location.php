<?php

namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\HoldRequestResultConsumer\Model\DataModel;

class Location extends DataModel
{
    /**
     * @var string
     */
    public $code = '';

    /**
     * @var string
     */
    public $label = '';

    /**
     * @var string
     */
    public $locationsApiSlug = '';

    /**
     * @var string
     */
    public $deliveryLocationTypes = '';

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLocationsApiSlug(): string
    {
        return $this->locationsApiSlug;
    }

    /**
     * @param string $locationsApiSlug
     */
    public function setLocationsApiSlug(string $locationsApiSlug)
    {
        $this->locationsApiSlug = $locationsApiSlug;
    }

    /**
     * @return string
     */
    public function getDeliveryLocationTypes(): string
    {
        return $this->deliveryLocationTypes;
    }

    /**
     * @param string $deliveryLocationTypes
     */
    public function setDeliveryLocationTypes(string $deliveryLocationTypes)
    {
        $this->deliveryLocationTypes = $deliveryLocationTypes;
    }
}
