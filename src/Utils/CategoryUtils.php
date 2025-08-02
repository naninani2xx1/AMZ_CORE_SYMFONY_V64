<?php

namespace App\Utils;

use App\Core\DataType\ArticleDataType;
use App\Core\DataType\CategoryDataType;

class CategoryUtils
{
    public static function getStrControllerByType($type, $method): string
    {
        if($type == CategoryDataType::TYPE_NEWS_ARTICLE){
            return "App\\Controller\\Admin\\Category\\NewsCategoryController::".$method;
        } elseif($type == CategoryDataType::TYPE_RECIPE_ARTICLE){
            return "App\\Controller\\Admin\\Category\\RecipeCategoryController::".$method;
        }
        return "App\\Controller\\Admin\\Category\\CategoryController::".$method;
    }
}