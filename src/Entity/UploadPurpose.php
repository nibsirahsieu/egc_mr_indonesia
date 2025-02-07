<?php 

namespace App\Entity;

enum UploadPurpose: int {
    case POST = 0;
    case CASE_STUDY = 1;
}