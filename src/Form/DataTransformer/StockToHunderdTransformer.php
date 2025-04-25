<?php

namespace App\Form\DataTransformer;

use App\Entity\Bead;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StockToHunderdTransformer implements DataTransformerInterface
{

    /**
     * Transforms stock amount to whole number.
     *
     * @param  int|null $value
     * @return double
     */
    public function Transform(mixed $value): string
    {

        $priceInDollar = number_format(($value / 100), 2, '.', ' ');

        return $priceInDollar;
    }

    public function reverseTransform(mixed $value): string
    {

        $priceInCent = (int)($value * 100);

        return $priceInCent;
    }
}
