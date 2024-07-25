<?php

namespace console\services;

use frontend\models\shop\Products;

interface FeedInterface
{
    /**
     * Set the products that are included in the feed.
     * @param Products[] $products
     */
    public function withProducts(array $products): void;

    /**
     * Export feed to file by specified path.
     * @param string $path Path to file with name of file and extension.
     * @return bool Its return is false when the service cannot write data to the file.
     */
    public function export(string $path): bool;
}
