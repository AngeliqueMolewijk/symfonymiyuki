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
     * Transforms cent to dollar amount.
     *
     * @param  int|null $value
     * @return double
     */
    public function Transform(mixed $value): string
    {
        // dd($value);
        // if (null === $value) {
        //     return;
        // }

        $priceInDollar = number_format(($value / 100), 2, '.', ' ');

        return $priceInDollar;
    }

    public function reverseTransform(mixed $value): string
    {
        // if (null === $value) {
        //     return;
        // }

        $priceInCent = (int)($value * 100);

        return $priceInCent;
    }
}
