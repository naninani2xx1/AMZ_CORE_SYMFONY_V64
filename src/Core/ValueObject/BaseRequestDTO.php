<?php

namespace App\Core\ValueObject;

use App\Core\DTO\RequestDataTableDTO;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseRequestDTO
{

    public function __construct(Request $request)
    {
        try{
            $this->autoLoadValue($request);
        }catch (\Exception $exception){}
    }

    /**
     * @throws \ReflectionException
     */
    private function autoLoadValue(Request $request): void
    {
        $setters = $this->getSetters();
        $dataPayload = $request->request->all(); // Data from request body
        $dataQueries = $request->query->all();   // Data from query string

        // Combine query and payload data, prioritizing payload if keys overlap
        $data = (array_merge($dataQueries, $dataPayload));
        foreach ($setters as $setter) {
            $property = strtolower(str_replace('set', '', $setter));

            if (property_exists(RequestDataTableDTO::class, ($property))) {
                $value = $data[$property] ?? null;
                call_user_func([$this, $setter], $value);
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function getSetters(): array
    {
        $setters = [];
        $reflectionClass = new ReflectionClass($this->referenceObject());

        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if (str_starts_with($method->getName(), 'set') && $method->getNumberOfParameters() === 1) {
                $setters[] = $method->getName();
            }
        }

        return $setters;
    }
    abstract public function referenceObject(): string;
}