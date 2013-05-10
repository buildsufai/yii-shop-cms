<?php

/**
 * IECartPosition
 *
 * @author Michael de Hart <derinus@gmail.com>
 * @version 0.1
 * @package ShoppingCart
 */
interface IECartPosition {
    /**
     * @return mixed id
     */
    public function getId();
    /**
     * @return float price
     */
    public function getPrice();
}