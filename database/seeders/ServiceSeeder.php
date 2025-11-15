<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => [
                    'ar' => 'خدمة التنظيف',
                    'en' => 'Cleaning Service'
                ],
                'description' => [
                    'ar' => [
                        'تنظيف شامل للمنزل',
                        'تنظيف النوافذ والمرايا',
                        'تنظيف المطابخ والحمامات',
                        'تنظيف السجاد والموكيت'
                    ],
                    'en' => [
                        'Complete house cleaning',
                        'Window and mirror cleaning',
                        'Kitchen and bathroom cleaning',
                        'Carpet and rug cleaning'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة الغسيل والكي',
                    'en' => 'Laundry and Ironing Service'
                ],
                'description' => [
                    'ar' => [
                        'غسيل الملابس',
                        'كي الملابس',
                        'تنظيف الملابس الحساسة',
                        'خدمة التوصيل'
                    ],
                    'en' => [
                        'Clothes washing',
                        'Clothes ironing',
                        'Delicate clothes cleaning',
                        'Delivery service'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة الصيانة',
                    'en' => 'Maintenance Service'
                ],
                'description' => [
                    'ar' => [
                        'صيانة الكهرباء',
                        'صيانة السباكة',
                        'صيانة الأجهزة',
                        'إصلاحات عامة'
                    ],
                    'en' => [
                        'Electrical maintenance',
                        'Plumbing maintenance',
                        'Appliance maintenance',
                        'General repairs'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة الطبخ',
                    'en' => 'Cooking Service'
                ],
                'description' => [
                    'ar' => [
                        'تحضير الوجبات اليومية',
                        'طبخ الأطباق التقليدية',
                        'تحضير الحلويات',
                        'تخطيط القوائم'
                    ],
                    'en' => [
                        'Daily meal preparation',
                        'Traditional dishes cooking',
                        'Dessert preparation',
                        'Menu planning'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة البستنة',
                    'en' => 'Gardening Service'
                ],
                'description' => [
                    'ar' => [
                        'ري النباتات',
                        'تقليم الأشجار',
                        'زراعة النباتات',
                        'صيانة الحدائق'
                    ],
                    'en' => [
                        'Plant watering',
                        'Tree pruning',
                        'Planting',
                        'Garden maintenance'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة رعاية الأطفال',
                    'en' => 'Childcare Service'
                ],
                'description' => [
                    'ar' => [
                        'رعاية الأطفال',
                        'مساعدة في الواجبات',
                        'أنشطة ترفيهية',
                        'رعاية مسائية'
                    ],
                    'en' => [
                        'Childcare',
                        'Homework assistance',
                        'Recreational activities',
                        'Evening care'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة رعاية المسنين',
                    'en' => 'Elderly Care Service'
                ],
                'description' => [
                    'ar' => [
                        'رعاية صحية',
                        'مساعدة يومية',
                        'مرافقة طبية',
                        'دعم نفسي'
                    ],
                    'en' => [
                        'Health care',
                        'Daily assistance',
                        'Medical accompaniment',
                        'Psychological support'
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'خدمة التوصيل',
                    'en' => 'Delivery Service'
                ],
                'description' => [
                    'ar' => [
                        'توصيل الطلبات',
                        'توصيل سريع',
                        'توصيل متعدد المحطات',
                        'تتبع الطلبات'
                    ],
                    'en' => [
                        'Order delivery',
                        'Fast delivery',
                        'Multi-stop delivery',
                        'Order tracking'
                    ]
                ],
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        $this->command->info('Services seeded successfully!');
    }
}
