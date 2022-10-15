<?php

namespace fw\base;

use fw\Db;
use Valitron\Validator;

abstract class Model{

    public $attributes = [];
    public $errors = [];
    public $rules = [];

    public function __construct(){
        Db::instance();
    }

    //writes only those properties whose names are defined in $attributes
    public function selectiveLoading($arrDataFrom){
        foreach ($this->attributes as $name => $value){
            if (isset($arrDataFrom[$name])){
                $this->attributes[$name] = $arrDataFrom[$name];
            }
        }
    }

    public function validate($data){
        Validator::langDir(WWW . '/castomLang');
        Validator::lang('ru');
        $validator = new Validator($data);
        $validator->rules($this->rules);
        if ($validator->validate()){
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }

    public function showValidageErors(){
        $errors = '<ul>';
        foreach ($this->errors as $error){
            foreach ($error as $message){
                $errors .= "<li>$message</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['errors'] = $errors;
    }

    public function saveInDbase($tableIn, $valid = true){
        if ($valid){
            $table = \R::dispense($tableIn);
        }else{
            $table = \R::xdispense($tableIn);
        }
        foreach ($this->attributes as $name => $value){
            $table->$name = $value;
        }
        return \R::store($table);
    }

    public function update($table, $id){
        $bean = \R::load($table, $id);
        foreach ($this->attributes as $attribute => $value){
            $bean->$attribute = $value;
        }
        return \R::store($bean);
    }

}