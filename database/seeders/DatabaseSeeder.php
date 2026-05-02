<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\Insight;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::put('company', [
            'name' => 'LAKE ZONE CHEMICALS LIMITED',
            'tagline' => 'Chemical sourcing, storage, and supply across Tanzania',
            'phone' => '+255 700 000 000',
            'whatsapp' => '+255 700 000 000',
            'email' => 'sales@lakezonechemicals.co.tz',
            'location' => 'Mwanza, Tanzania',
            'map_url' => 'https://maps.google.com/?q=Mwanza%2C%20Tanzania',
            'about' => 'Lake Zone Chemicals Limited is a Tanzania-based chemical supplier focused on safe, compliant, and responsive procurement for businesses that cannot afford delays or inconsistent quality. We help customers choose the right grades, package sizes, and logistics route for their operation.',
            'products_stat' => '120+',
            'regions_stat' => '8',
            'support_stat' => '24h',
        ]);

        Setting::put('seo', [
            'title' => 'Lake Zone Chemicals Limited | Chemical Supplier in Mwanza, Tanzania',
            'description' => 'Lake Zone Chemicals Limited supplies industrial chemicals, water treatment chemicals, laboratory reagents, agricultural chemicals, and mining chemicals across Mwanza and Tanzania.',
            'keywords' => 'chemical supplier Tanzania, chemicals Mwanza, water treatment chemicals Tanzania, industrial chemicals Tanzania, laboratory reagents Tanzania',
            'canonical_url' => 'https://www.lakezonechemicals.co.tz/',
            'image_url' => 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?auto=format&fit=crop&w=1200&q=82',
        ]);

        $products = [
            ['Caustic Soda Flakes', 'Industrial', 'Industrial grade', '25 kg bags', 'For soap production, cleaning processes, pH control, and manufacturing operations.'],
            ['Hydrochloric Acid', 'Industrial', 'Commercial grade', '30 L jerricans / drums', 'Used for scale removal, pH adjustment, metal treatment, and controlled industrial cleaning.'],
            ['Sodium Hypochlorite', 'Water Treatment', 'Water treatment grade', '20 L / 30 L jerricans', 'Disinfection chemical for water treatment, sanitation, and hygiene programs.'],
            ['Aluminium Sulphate', 'Water Treatment', 'Water treatment grade', '25 kg bags', 'Coagulant for drinking water treatment, wastewater clarification, and process water systems.'],
            ['Laboratory Reagents', 'Laboratory', 'AR / LR grades', 'Bottle packs', 'Common acids, bases, indicators, salts, and solvents for schools, labs, and QA teams.'],
            ['Copper Sulphate', 'Agriculture', 'Agricultural grade', '25 kg bags', 'Agricultural and industrial applications where controlled copper content is required.'],
        ];

        foreach ($products as $index => [$name, $category, $grade, $packaging, $description]) {
            Product::query()->updateOrCreate(['name' => $name], compact('category', 'grade', 'packaging', 'description') + ['sort_order' => $index + 1, 'is_active' => true]);
        }

        $industries = [
            ['Water Treatment', 'Municipal, institutional, and private water systems needing coagulants, disinfectants, and pH chemicals.'],
            ['Manufacturing', 'Cleaning, production, processing, and maintenance chemicals for factories and workshops.'],
            ['Mining', 'Supply coordination for process chemicals, reagents, and safe logistics into active mining regions.'],
            ['Agriculture', 'Chemical inputs and support products for farms, agro-dealers, and regional distributors.'],
            ['Laboratories', 'Reagents, consumables, and documentation for schools, clinics, QA labs, and research teams.'],
            ['Hospitality', 'Cleaning, sanitation, pool, and maintenance chemicals for hotels and institutions.'],
        ];

        foreach ($industries as $index => [$title, $description]) {
            Industry::query()->updateOrCreate(['title' => $title], compact('description') + ['sort_order' => $index + 1, 'is_active' => true]);
        }

        $insights = [
            ['How to request a faster quotation', 'Procurement note', 'Include product name, grade, quantity, delivery city, packaging preference, and any SDS or COA requirement.'],
            ['Storage matters for chemical performance', 'Safety note', 'Keep oxidizers, acids, bases, and solvents separated, labelled, ventilated, and protected from direct heat.'],
            ['Choosing between grades', 'Buyer guide', 'Industrial, food, laboratory, and water treatment grades can behave differently in compliance and documentation.'],
        ];

        foreach ($insights as $index => [$title, $label, $body]) {
            Insight::query()->updateOrCreate(['title' => $title], compact('label', 'body') + ['sort_order' => $index + 1, 'is_active' => true]);
        }
    }
}
