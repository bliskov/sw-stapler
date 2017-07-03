<?php

namespace KjBeDataIntegration\Persistence;

class PimQueryContainer
{

    /**
     * @return array
     */
    public function getManufacturerColumns()
    {
        $parentId = "3330001";

        return [
            "m.id" => "categoryId",
            "m.name" => "description",
            "$parentId" => "parentID",
            "@rank:=@rank+1" => "position",
        ];
    }

    /**
     * @return array
     */
    public function getVehicleColumns()
    {
        return [
            "v.id" => "categoryId",
            "v.name" => "description",
            "m.id" => "parentID",
            "@rank:=@rank+1" => "position",
        ];
    }

    /**
     * @return array
     */
    public function getBatteryColumns()
    {
        return [
            "b.id" => "additionalText",
            "id" => "mainnumber",
#            "id" => "ordernumber",
            "b.id" => "articleID",
            "b.name" => "name",
            "b.slug" => "description",
            "19.00" => "tax",
            "1" => "active", // set active
            "b.voltage" => "voltage",
            "b.capacity" => "capacity",
/*
            "0.00" => "price_EK",
            "0.00" => "pseudoprice_EK",
            "0.00" => "baseprice_EK",
            "0.00" => "price_H",
            "0.00" => "pseudoprice_H",
            "0.00" => "baseprice_H",
*/
            "b.width" => "width",
            "b.height" => "height",
            "b.length" => "length",
        ];


    }

    /**
     * @return array
     */
    public function getAdditionalBatteryHeaderColumns()
    {
        return [
            "ean",
            "ordernumber",
            "price",
            "supplier",
            "categories",
        ];

    }

    /**
     * @return string
     */
    public function getManufacturerQuery()
    {
        $fields = $this->getSelectFields($this->getManufacturerColumns());

        return "select $fields
                from manufacturer as m
                where m.name != ''
                order by m.name;
              ";
    }

    /**
     * @return string
     */
    public function getVehicleQuery()
    {
        $fields = $this->getSelectFields($this->getVehicleColumns());
        # and v.id > 1

        return "select $fields
                from manufacturer as m
                left join vehicle as v on v.manufacturer_id = m.id
                where v.name != ''
                and v.id != '1'
                and m.id != '1'
                order by v.name";
    }

    /**
     * @return string
     */
    public function getBatteryQuery()
    {
        $fields = $this->getSelectFields($this->getBatteryColumns());

        return "select $fields
                from battery as b
                order by b.id";
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getAvailabilityQueryByBatteryName($name, $voltage, $capacity)
    {
        $fields = "availability.id as ean, availability.item_number as ordernumber, CAST(availability.price/100 as DECIMAL(10,2)) price, availability.supplier_id as supplier";

        return "select $fields
                from availability
                where availability.name = '$name'
                and availability.voltage = '$voltage'
                and availability.capacity = '$capacity'
                and availability.id != ''
                and availability.price IS NOT NULL
                and availability.name IS NOT NULL
                order by priority limit 1";
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function getVehiclesByBatteryId($id)
    {
        return "select vehicle_id as categories
                from battery_usage
                where battery_id = '$id'";
    }

    /**
     * @param array $columns
     * @param string $fields
     *
     * @return string
     */
    protected function getSelectFields(array $columns, $fields = "")
    {
        foreach ($columns as $key => $value) {
            $fields .= " $key as $value,";
        }

        return mb_substr($fields, 0, -1);
    }
}
