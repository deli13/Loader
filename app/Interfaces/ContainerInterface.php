<?php


namespace deli13\Loader\Interfaces;

/**
 * Interface ContainerInterface
 * Интерфейс контейнера
 * @package deli13\Loader\Interfaces
 */
interface ContainerInterface
{
    /**
     * Получение зависимости в контейнере
     * @param string $name
     * @return DependencyInterface
     */
    public function get(string $name):DependencyInterface;

    /**
     * Установка зависимости
     * @param string $name
     * @param DependencyInterface $dependency
     * @return mixed
     */
    public function set(string $name,DependencyInterface $dependency);

    /**
     * Проверка контейнера на наличие зависимостей
     * @param string $name
     * @return bool
     */
    public function has(string $name):bool;

}