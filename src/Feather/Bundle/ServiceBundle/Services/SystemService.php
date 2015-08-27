<?php

/*
 * This file is part of Feather application.
 *
 * (c) William Rudent <william.rudent@gmail.com>
 *
 * Copyright © 2015 William Rudent <william.rudent@gmail.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the “Software”), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject
 * to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * The Software is provided “as is”, without warranty of any kind, express or implied, including but not
 * limited to the warranties of merchantability, fitness for a particular purpose and noninfringement.
 * In no event shall the authors or copyright holders be liable for any claim, damages or other liability,
 * whether in an action of contract, tort or otherwise, arising from, out of or in connection with the
 * software or the use or other dealings in the Software. »
 */

namespace Feather\Bundle\ServiceBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Transmission\Transmission;
use Transmission\Client;

/**
 * @author William Rudent <william.rudent@gmail.com>
 */
class SystemService extends Controller
{
    /**
     * Get total disk space
     *
     * @author William Rudent <william.rudent@gmail.com>
     *
     * @param string $bytes
     *
     * @param bool $unit
     *
     * @return integer $space
     */
    public function getDiskspace($bytes, $unit = false)
    {
        $space = disk_total_space($bytes);
        $type = [
            'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'
        ];
        $i = 0;

        while($space >= 1024)
        {
            $space /= 1024;
            $i++;
        }

        $space = round($space);

        if ($unit == true) {
            $space = $space . ' ' . $type[$i];
        }

        return $space;
    }

    /**
     * Get disk space left
     *
     * @author William Rudent <william.rudent@gmail.com>
     *
     * @param string $bytes
     *
     * @param bool $unit
     *
     * @return integer $space
     */
    public function getDiskleft($bytes, $unit = false)
    {
        $space = disk_free_space($bytes);
        $type = [
            'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'
        ];
        $i = 0;

        while($space >= 1024)
        {
            $space /= 1024;
            $i++;
        }

        $space = round($space);

        if ($unit == true) {
            $space = $space . ' ' . $type[$i];
        }

        return $space;
    }

    /**
     * Get disk usage in percent
     *
     * @author William Rudent <william.rudent@gmail.com>
     *
     * @param string $drive
     *
     * @return integer $diskusage
     */
    public function getDiskusage($drive = null)
    {
        if ($drive) {
            $diskspace = $this->getDiskspace($drive);
            $diskleft = $this->getDiskleft($drive);
        } else {
            $diskspace = $this->getDiskspace('/');
            $diskleft = $this->getDiskleft('/');
        }

        $diskusage = 100 - ( ( $diskleft * 100 ) / $diskspace );

        return round($diskusage);
    }
}