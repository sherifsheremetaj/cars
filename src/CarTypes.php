<?php namespace SherifSheremetaj\Cars;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use SherifSheremetaj\Cars\helpers\CSVHelper;
use SherifSheremetaj\Cars\helpers\XMLHelper;

class CarTypes
{
    public function datasetPath(): string
    {
        return __DIR__ . '/data/car_types.json';
    }

    /**
     * @throws Exception
     */
    public function getTypes(string $type = DataTypes::JSON): array|string
    {
        if (!in_array($type, DataTypes::ALL, true)) {
            throw new InvalidArgumentException("Invalid type provided: $type");
        }

        return match ($type) {
            DataTypes::JSON => $this->loadTypesJson(),
            DataTypes::CSV  => $this->loadTypesCsv(),
            DataTypes::XML  => $this->loadTypesXml(),
            default        => throw new RuntimeException("Unhandled type: $type"),
        };
    }

    public function loadTypesJson(): array|string
    {
        $jsonPath = $this->datasetPath();
        $jsonData = file_get_contents($jsonPath);

        if ($jsonData === false) {
            return []; // Handle error case if file reading fails
        }

        return $jsonData;
    }

    public function loadTypesCsv(): string
    {
        return CSVHelper::readAsCSV($this->datasetPath());
    }

    /**
     * @throws Exception
     */
    public function loadTypesXml(): string
    {
        return XMLHelper::readAsXML($this->datasetPath(), 'carTypes', 'carType');
    }
}
