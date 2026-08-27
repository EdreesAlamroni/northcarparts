<?php

namespace Database\Seeders;

use App\Models\News;
use App\ModelStates\News\States\Visible;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsItems = [
            [
                'slug' => 'north-car-parts-launches-new-oil-filter-line',
                'title' => 'North Car Parts تطلق خط فلاتر زيت جديد',
                'content' => 'أعلنت North Car Parts عن إطلاق خط جديد من فلاتر الزيت المصممة لتلبية معايير الأداء العالية في السوق المحلي والإقليمي. يتميز الخط الجديد بكفاءة تصفية محسّنة ومقاومة أعلى للضغط.',
                'published_at' => '2026-01-15',
            ],
            [
                'slug' => 'partnership-with-leading-oem-suppliers',
                'title' => 'شراكة مع موردي OEM الرائدين',
                'content' => 'وقّعت North Car Parts شراكات جديدة مع عدد من موردي قطع الغيار الأصلية لتوسيع نطاق منتجاتها وتقديم بدائل موثوقة للعملاء في قطاع السيارات.',
                'published_at' => '2026-02-10',
            ],
            [
                'slug' => 'quality-testing-lab-expansion',
                'title' => 'توسعة مختبر فحص الجودة',
                'content' => 'أكملت الشركة توسعة مختبر فحص الجودة لدعم اختبارات فلاتر الهواء والوقود والمقصورة، بما يضمن مطابقة المنتجات للمواصفات قبل طرحها في السوق.',
                'published_at' => '2026-03-05',
            ],
        ];

        foreach ($newsItems as $newsItem) {
            News::query()->firstOrCreate(
                ['slug' => $newsItem['slug']],
                [
                    'uuid' => Str::uuid7()->toString(),
                    'title' => $newsItem['title'],
                    'content' => $newsItem['content'],
                    'published_at' => $newsItem['published_at'],
                    'state' => Visible::name(),
                ],
            );
        }
    }
}
