<?php

namespace System\Database\ORM;

use System\Database\Traits\HasAttribute;
use System\Database\Traits\HasCRUD;
use System\Database\Traits\HasMethodCaller;
use System\Database\Traits\HasQueryBuilder;
use System\Database\Traits\HasRelation;

abstract class Model
{
    use HasAttribute, HasCRUD, HasMethodCaller, HasQueryBuilder, HasRelation;

    /**
     * نام جدول دیتابیس
     */
    protected $table;

    /**
     * فیلدهای قابل استفاده برای create و update
     */
    protected $fillable = [];

    /**
     * فیلدهایی که نباید هنگام تبدیل به attribute در دسترس باشند
     */
    protected $hidden = [];

    /**
     * نوع داده attributeها
     */
    protected $casts = [];

    /**
     * کلید اصلی جدول
     */
    protected $primaryKey = 'id';

    /**
     * نام فیلد created_at
     */
    protected $createdAt = 'created_at';

    /**
     * نام فیلد updated_at
     */
    protected $updatedAt = 'updated_at';

    /**
     * نام فیلد حذف نرم
     */
    protected $deletedAt = null;

    /**
     * نگهداری attributeهای مربوط به یک Model
     *
     * این آرایه جایگزین Dynamic Properties می‌شود.
     */
    protected $attributes = [];

    /**
     * collection نتایج query
     */
    protected $collection = [];
}