<?php

namespace App\Utils;

use App\Core\DataType\ArticleDataType;

class ArticleUtils
{
    public static function getStrControllerByType($type, $method): string
    {
        if($type == ArticleDataType::TYPE_NEW_ARTICLE){
            return "App\\Controller\\Admin\\Article\\NewsArticleController::".$method;
        } elseif($type == ArticleDataType::TYPE_RECIPE_ARTICLE){
            return "App\\Controller\\Admin\\Article\\RecipeArticleController::".$method;
        }
        return "App\\Controller\\Admin\\Article\\ArticleController::".$method;
    }
}