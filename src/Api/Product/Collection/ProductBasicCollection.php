<?php declare(strict_types=1);

namespace Shopware\Api\Product\Collection;

use Shopware\Api\Entity\EntityCollection;
use Shopware\Api\Product\Struct\ProductBasicStruct;
use Shopware\Api\Seo\Collection\SeoUrlBasicCollection;
use Shopware\Api\Tax\Collection\TaxBasicCollection;
use Shopware\Api\Unit\Collection\UnitBasicCollection;

class ProductBasicCollection extends EntityCollection
{
    /**
     * @var ProductBasicStruct[]
     */
    protected $elements = [];

    public function get(string $id): ? ProductBasicStruct
    {
        return parent::get($id);
    }

    public function current(): ProductBasicStruct
    {
        return parent::current();
    }

    public function getParentIds(): array
    {
        return $this->fmap(function (ProductBasicStruct $product) {
            return $product->getParentId();
        });
    }

    public function filterByParentId(string $id): self
    {
        return $this->filter(function (ProductBasicStruct $product) use ($id) {
            return $product->getParentId() === $id;
        });
    }

    public function getTaxIds(): array
    {
        return $this->fmap(function (ProductBasicStruct $product) {
            return $product->getTaxId();
        });
    }

    public function filterByTaxId(string $id): self
    {
        return $this->filter(function (ProductBasicStruct $product) use ($id) {
            return $product->getTaxId() === $id;
        });
    }

    public function getManufacturerIds(): array
    {
        return $this->fmap(function (ProductBasicStruct $product) {
            return $product->getManufacturerId();
        });
    }

    public function filterByManufacturerId(string $id): self
    {
        return $this->filter(function (ProductBasicStruct $product) use ($id) {
            return $product->getManufacturerId() === $id;
        });
    }

    public function getUnitIds(): array
    {
        return $this->fmap(function (ProductBasicStruct $product) {
            return $product->getUnitId();
        });
    }

    public function filterByUnitId(string $id): self
    {
        return $this->filter(function (ProductBasicStruct $product) use ($id) {
            return $product->getUnitId() === $id;
        });
    }

    public function getPriceGroupIds(): array
    {
        return $this->fmap(function (ProductBasicStruct $product) {
            return $product->getPriceGroupId();
        });
    }

    public function filterByPriceGroupId(string $id): self
    {
        return $this->filter(function (ProductBasicStruct $product) use ($id) {
            return $product->getPriceGroupId() === $id;
        });
    }

    public function getTaxes(): TaxBasicCollection
    {
        return new TaxBasicCollection(
            $this->fmap(function (ProductBasicStruct $product) {
                return $product->getTax();
            })
        );
    }

    public function getManufacturers(): ProductManufacturerBasicCollection
    {
        return new ProductManufacturerBasicCollection(
            $this->fmap(function (ProductBasicStruct $product) {
                return $product->getManufacturer();
            })
        );
    }

    public function getUnits(): UnitBasicCollection
    {
        return new UnitBasicCollection(
            $this->fmap(function (ProductBasicStruct $product) {
                return $product->getUnit();
            })
        );
    }

    public function getContextPriceIds(): array
    {
        $ids = [];
        foreach ($this->elements as $element) {
            foreach ($element->getContextPrices()->getIds() as $id) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    public function getContextPrices(): ProductContextPriceBasicCollection
    {
        $collection = new ProductContextPriceBasicCollection();
        foreach ($this->elements as $element) {
            $collection->fill($element->getContextPrices()->getElements());
        }

        return $collection;
    }

    public function getPrices(): PriceCollection
    {
        return new PriceCollection($this->fmap(function (ProductBasicStruct $product) {
            return $product->getPrice();
        }));
    }

    public function getCanonicalUrls(): SeoUrlBasicCollection
    {
        return new SeoUrlBasicCollection(
            $this->fmap(function (ProductBasicStruct $product) {
                return $product->getExtension('canonicalUrl');
            })
        );
    }

    protected function getExpectedClass(): string
    {
        return ProductBasicStruct::class;
    }
}