<?php

namespace app\models;

use fw\App;

class Category extends AppModel{

    public $attributes = [//attributes of category, use to write a new category
            'title' => '',
            'parent_id' => '',
            'keywords' => '',
            'description' => '',
            'alias' => '',
        ];

    public $rules = [//for server validation
        'required' => [
            ['title'],
        ],
    ];

    public function getNestedCategories($currentId){
        $categories = App::$appContainer->getParameter('categories');
        $nestedId = '';//list nested categories
        foreach ($categories as $k => $category){
            if($category['parent_id'] == $currentId){
                $nestedId .= $k . ',';
                $nestedId .= $this->getNestedCategories($k);//recursively
            }
        }
        return $nestedId;
    }

}