<?php
namespace Database\Seeders;

use App\Models\Furniture;
use Illuminate\Database\Seeder;

class FurnitureSeeder extends Seeder
{
    public function run(): void
    {
        $u = fn ($id) => "https://images.unsplash.com/photo-$id?auto=format&fit=crop&w=900&q=80";
        $rows = [
            ['Sofa Velvet Navy 3 Dudukan', 'Sofa', 'Beludru dan rangka kayu jati', 8900000, '220 x 92 x 80 cm', $u('1555041469-a586c61ea9bc')],
            ['Sofa Lounge Linen Grey', 'Sofa', 'Linen premium dan busa HR', 6750000, '200 x 90 x 78 cm', $u('1540574163026-643ea20ade25')],
            ['Meja Makan Jati Solid', 'Meja', 'Kayu jati solid finishing natural', 7400000, '180 x 90 x 75 cm', $u('1493663284031-b7e3aefcae8e')],
            ['Meja Kopi Marmer Bulat', 'Meja', 'Marmer dan kaki besi hitam', 3250000, 'Diameter 80 x 42 cm', $u('1586023492125-27b2c045efd7')],
            ['Kursi Makan Scandinavian', 'Kursi', 'Kayu oak dan jok kulit sintetis', 1450000, '48 x 52 x 84 cm', $u('1567538096630-e0c55bd6374c')],
            ['Kursi Lounge Premium', 'Kursi', 'Kain boucle dan kaki kayu', 3150000, '75 x 78 x 80 cm', $u('1519710164239-da123dc03ef4')],
            ['Tempat Tidur King Walnut', 'Tempat Tidur', 'Kayu walnut dan headboard berlapis', 11500000, '210 x 190 x 110 cm', $u('1505693416388-ac5ce068fe85')],
            ['Tempat Tidur Queen Minimalis', 'Tempat Tidur', 'Kayu mahoni dan kain tenun', 8200000, '210 x 170 x 100 cm', $u('1616594039964-ae9021a400a0')],
            ['Lemari Pakaian 3 Pintu', 'Lemari', 'Kayu lapis HPL anti rayap', 6300000, '150 x 55 x 200 cm', $u('1524758631624-e2822e304c36')],
            ['Rak Pajangan Aksen Emas', 'Dekorasi', 'Besi powder coating dan kaca', 2100000, '90 x 35 x 160 cm', $u('1618220179428-22790b461013')],
        ];
        foreach ($rows as [$name, $category, $material, $price, $dimensions, $image]) {
            Furniture::create(compact('name', 'category', 'material', 'price', 'dimensions', 'image'));
        }
    }
}