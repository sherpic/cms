<?php
namespace Soda\Events;


class NavigationWasRendered {

    public function __construct($input = NULL){
        if($input){
            $this->input = $input;
        }
    }
}