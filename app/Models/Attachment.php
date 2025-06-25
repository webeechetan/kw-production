<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Attachment extends Model
{
    use HasFactory;

    public function getCanViewAttribute(){
        $type = Storage::mimeType($this->attachment_path);
        if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/jpg'){
            return true;
        }
        return false;
    }

    public function getThumbnailAttribute(){
        $extension = pathinfo($this->attachment_path, PATHINFO_EXTENSION);
        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
            return asset('').'assets/images/icons/image.svg';
        } elseif ($extension == 'txt') {
            return asset('').'assets/images/icons/txt.svg';
        } elseif ($extension == 'pdf') {
            return asset('').'assets/images/icons/pdf.svg';
        } elseif ($extension == 'html'){
            return asset('').'assets/images/icons/html.svg';
        } elseif ($extension == 'psd'){
            return asset('').'assets/images/icons/psd.svg';
        } elseif ($extension == 'xlsx'){
            return asset('').'assets/images/icons/xlsx.svg';
        } elseif ($extension == 'sql'){
            return asset('').'assets/images/icons/sql.svg';
        } elseif ($extension == 'docx'){
            return asset('').'assets/images/icons/docx.svg';
        }else{
            return null;
        }
    }
}
