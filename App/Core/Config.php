<?php

namespace Core;

/**
 * Class Config
 * @package Core
 * @description Configuration class
 *  This was meant to be some fancy configuration class, but for now static properties will do.
 *
 */
class Config extends AUtility
{

    public static string $defaultImage = '/svg/image_regular.svg';

    public static bool $dev = false;

    public static string $protocol = 'http';
}