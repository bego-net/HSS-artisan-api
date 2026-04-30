<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Services ─────────────────────────────────
        $services = [
            [
                'title'       => 'Custom Software Development',
                'description' => 'We build reliable, scalable software solutions tailored to your unique business workflows and daily operations.',
                'content'     => '<h2>End-to-End Software Solutions</h2><p>Our team specializes in building custom software that solves real business problems. From initial consultation to deployment and maintenance, we handle every step of the development lifecycle.</p><h3>What We Offer</h3><ul><li>Business process automation</li><li>Enterprise resource planning (ERP) systems</li><li>Customer relationship management (CRM) tools</li><li>Inventory and supply chain management</li><li>Custom dashboards and reporting tools</li></ul><p>Every solution we build is designed for performance, security, and long-term maintainability.</p>',
                'icon'        => 'code',
            ],
            [
                'title'       => 'Web Design & Development',
                'description' => 'Design and build polished websites and digital platforms that balance performance with clean user experience.',
                'content'     => '<h2>Modern Web Experiences</h2><p>We create responsive, fast-loading websites that look stunning on every device. Our approach combines modern design principles with robust engineering.</p><h3>Our Web Services</h3><ul><li>Corporate websites and landing pages</li><li>E-commerce platforms</li><li>Web applications (SaaS)</li><li>Content management systems</li><li>API development and integration</li></ul>',
                'icon'        => 'globe',
            ],
            [
                'title'       => 'Mobile App Development',
                'description' => 'Create mobile-first applications that bring services and productivity into users\' hands.',
                'content'     => '<h2>Native & Cross-Platform Apps</h2><p>We develop high-performance mobile applications for both iOS and Android platforms, ensuring a seamless user experience across all devices.</p><h3>Technologies We Use</h3><ul><li>React Native for cross-platform development</li><li>Flutter for beautiful native interfaces</li><li>Swift for iOS-native applications</li><li>Kotlin for Android-native applications</li></ul>',
                'icon'        => 'phone',
            ],
            [
                'title'       => 'UI/UX Design',
                'description' => 'Craft intuitive, visually compelling interfaces that delight users and drive engagement.',
                'content'     => '<h2>Design That Converts</h2><p>Great design is more than aesthetics — it\'s about creating experiences that guide users naturally toward their goals. Our design process is research-driven and user-centered.</p><h3>Our Process</h3><ul><li>User research and persona development</li><li>Wireframing and prototyping</li><li>Visual design and branding</li><li>Usability testing</li><li>Design system creation</li></ul>',
                'icon'        => 'design',
            ],
            [
                'title'       => 'Cloud & DevOps',
                'description' => 'Optimize your infrastructure with cloud solutions and automated deployment pipelines.',
                'content'     => '<h2>Scalable Infrastructure</h2><p>We help businesses migrate to the cloud and implement modern DevOps practices for faster, more reliable software delivery.</p><h3>Services Include</h3><ul><li>Cloud migration (AWS, GCP, Azure)</li><li>CI/CD pipeline setup</li><li>Container orchestration (Docker, Kubernetes)</li><li>Infrastructure as Code</li><li>Monitoring and alerting</li></ul>',
                'icon'        => 'cloud',
            ],
            [
                'title'       => 'IT Consulting',
                'description' => 'Strategic technology guidance to help your business make informed decisions and stay competitive.',
                'content'     => '<h2>Expert Technology Guidance</h2><p>Our consulting services help organizations align their technology strategy with business objectives, ensuring maximum ROI on every tech investment.</p><h3>What We Cover</h3><ul><li>Technology stack assessment</li><li>Digital transformation roadmaps</li><li>Security audits and compliance</li><li>Team training and mentorship</li><li>Vendor evaluation and selection</li></ul>',
                'icon'        => 'consult',
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['title' => $service['title']], $service);
        }

        // ── Products ─────────────────────────────────
        $products = [
            [
                'title'       => 'Hawi ERP System',
                'description' => 'A comprehensive enterprise resource planning solution designed for mid-sized businesses. Manage finance, HR, inventory, and operations from a single dashboard.',
                'image'       => 'products/placeholder.png',
            ],
            [
                'title'       => 'Hawi POS Terminal',
                'description' => 'A modern point-of-sale system built for retail and hospitality. Fast checkout, inventory tracking, and real-time sales analytics.',
                'image'       => 'products/placeholder.png',
            ],
            [
                'title'       => 'Hawi CRM Platform',
                'description' => 'Customer relationship management made simple. Track leads, manage contacts, automate follow-ups, and close deals faster.',
                'image'       => 'products/placeholder.png',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['title' => $product['title']], $product);
        }

        // ── Testimonials ─────────────────────────────
        $testimonials = [
            [
                'name'    => 'Abebe Kebede',
                'role'    => 'CEO, TechEthiopia',
                'message' => 'Hawi Software transformed our entire business workflow. Their custom ERP system saved us countless hours and significantly reduced operational costs.',
                'image'   => 'testimonials/placeholder.png',
            ],
            [
                'name'    => 'Sara Mohammed',
                'role'    => 'CTO, DigitalAddis',
                'message' => 'The team at Hawi delivered our mobile app ahead of schedule and exceeded all expectations. Their attention to detail and technical expertise is outstanding.',
                'image'   => 'testimonials/placeholder.png',
            ],
            [
                'name'    => 'Daniel Tesfaye',
                'role'    => 'Founder, CloudNet Solutions',
                'message' => 'Working with Hawi has been an incredible experience. They understood our vision from day one and built a platform that our customers love.',
                'image'   => 'testimonials/placeholder.png',
            ],
            [
                'name'    => 'Meron Hailu',
                'role'    => 'Director, EastAfrica Logistics',
                'message' => 'Their cloud migration service was seamless. Zero downtime, improved performance, and our infrastructure costs dropped by 40%. Highly recommended!',
                'image'   => 'testimonials/placeholder.png',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(['name' => $testimonial['name']], $testimonial);
        }

        // ── Partners ─────────────────────────────────
        $partners = [
            ['name' => 'TechEthiopia', 'logo' => 'partners/placeholder.png'],
            ['name' => 'DigitalAddis', 'logo' => 'partners/placeholder.png'],
            ['name' => 'CloudNet Solutions', 'logo' => 'partners/placeholder.png'],
            ['name' => 'EastAfrica Logistics', 'logo' => 'partners/placeholder.png'],
            ['name' => 'Addis Innovation Hub', 'logo' => 'partners/placeholder.png'],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(['name' => $partner['name']], $partner);
        }
    }
}
