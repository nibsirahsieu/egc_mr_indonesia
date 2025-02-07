<?php 

namespace App\Entity;

enum PostStatus: int {
    case DRAFT = 0;
    case PUBLISHED = 1;
}