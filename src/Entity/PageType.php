<?php 

namespace App\Entity;

enum PageType: int {
    case HOME = 0;
    case ABOUT = 1;
    case SECTORS = 2;
    case SERVICES = 3;
    case CASE_STUDIES = 4;
    case INSIGHTS = 5;
    case CONTACT_US = 6;

    public function label(): string
    {
        return match($this) {
            PageType::HOME => 'Home',
            PageType::ABOUT => 'About',
            PageType::SECTORS => 'Sectors',
            PageType::SERVICES => 'Services',
            PageType::CASE_STUDIES => 'Case Studies',
            PageType::INSIGHTS => 'Insights',
            PageType::CONTACT_US => 'Contact Us',
        };
    }
}