<?php

$faker = Faker\Factory::create();
$faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

$products = [];



$id = 1;
for ($i = 0; $i < 10; $i++) {
  $products[] = [
    'id' => $id++,
    'sku' => $faker->ean13,
    'name' => $faker->productName,
    'img' => $faker->imageUrl(),
    'price' => $faker->randomFloat(2, 1, 9999)

  ];
}
return $products;
