<?php

namespace Brander\Custom\FastFood\WebService;

class GetMenu extends \Brander\Custom\FastFood\WebService
{
    protected $path = ['#document', 'string', '#document', 'Menu'];


    /**
     * С параметром и без параметра "Brand" сервис возвращает одинактовый набор
     * Массив меню состоит из двух элементов
     * Path - объект (Информация о брэнде)
     *
     * Categories - массив объектов (Группы товаров) группа идентифицируется полем Code
     * Categories[i]['Categories'] массив объектов (Группы товаров) группа идентифицируется полем Code
     * Структура рекурсивная неограничееого уровня вложенности
     * Categories[i]['Categories'][j]['Categories'][k]['Categories'] ... ... ... ... ... ...
     *
     * Categories[i]['Items'] - массив обектов Товары идентифицируется полем Code
     * связаны с категориями полем ParentCode Categories[i]['Code'] = Categories[i]['Items']['ParentCode']
     * могут соержать массив Модификаторы Modificators идентифицируется полем Code
     * Модификатор является позицией из Items (Товаров), который связаны значением Code 
     *
     * В объекте Categories[i] может содержаться один массив категорий Categories[i]['Categories']
     * или один массив товаров Categories[i]['Items'] или одновременно Categories[i]['Categories'] и Categories[i]['Items']
     *
     */
    public function get($brand = '')
    {
        return $this->getService('GetMenu', ['Brand' => $brand]);
    }
}
