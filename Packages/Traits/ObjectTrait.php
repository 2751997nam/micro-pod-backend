<?php

namespace App\Packages\Traits;

trait ObjectTrait
{
    public function set($key, $value)
    {
        //kiểm tra xem trong class có tồn tại thuộc tính không
        if (property_exists($this, $key)) {
            //tiến hành gán giá trị
            $this->$key = $value;
        } else {
            die('Không tồn tại thuộc tính');
        }
    }

    public function get($key)
    {
        //kiểm tra xem trong class có tồn tại thuộc tính không
        if (property_exists($this, $key)) {
            //tiến hành lấy giá trị
            return $this->$key;
        } else {
            die('Không tồn tại thuộc tính');
        }
    }
}