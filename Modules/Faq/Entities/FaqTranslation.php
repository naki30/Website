<?php namespace Modules\Faq\Entities;

use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'faq__faqs_translations';
}