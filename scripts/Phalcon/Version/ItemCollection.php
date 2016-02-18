<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2015 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  |          Ivan Zinovyev <vanyazin@gmail.com>                            |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Version;

/**
 * Class ItemCollection.
 * The item collection lets you to work with an abstract ItemInterface.
 *
 * @package     Phalcon\Version
 * @copyright   Copyright (c) 2011-2015 Phalcon Team (team@phalconphp.com)
 * @license     New BSD License
 */
class ItemCollection
{
    /**
     * Incremental version item
     *
     * @const int
     */
    const TYPE_INCREMENTAL = 1;

    /**
     * Timestamp prefixed version item
     *
     * @const int
     */
    const TYPE_TIMESTAMP = 2;

    /**
     * @var int
     */
    static $type = self::TYPE_INCREMENTAL;

    /**
     * Sort items in the ascending order
     *
     * @param ItemInterface[] $versions
     *
     * @return ItemInterface[]
     */
    public static function sortAsc(array $versions)
    {
        $sortData = array();
        foreach ($versions as $version) {
            $sortData[$version->getStamp()] = $version;
        }
        ksort($sortData);

        return array_values($sortData);
    }

    /**
     * Sort items in the descending order
     *
     * @param ItemInterface[] $versions
     *
     * @return ItemInterface[]
     */
    public static function sortDesc(array $versions)
    {
        $sortData = array();
        foreach ($versions as $version) {
            $sortData[$version->getStamp()] = $version;
        }
        krsort($sortData);

        return array_values($sortData);
    }

    /**
     * Get the maximum value from the list of version items
     *
     * @param array $versions
     *
     * @return null|ItemInterface
     */
    public static function maximum(array $versions)
    {
        if (count($versions) == 0) {
            return null;
        }
        $versions = self::sortDesc($versions);

        return $versions[0];
    }

    /**
     * Get all the versions between two limitary version items
     *
     * @param ItemInterface   $initialVersion
     * @param ItemInterface   $finalVersion
     * @param ItemInterface[] $versions
     *
     * @return array
     */
    public static function between(
        ItemInterface $initialVersion,
        ItemInterface $finalVersion,
        array $versions
    ) {
        $versions = self::sortAsc($versions);

        if (!is_object($initialVersion)) {
            $initialVersion = new self($initialVersion);
        }

        if (!is_object($finalVersion)) {
            $finalVersion = new self($finalVersion);
        }

        $betweenVersions = array();
        if ($initialVersion->getStamp() == $finalVersion->getStamp()) {
            return $betweenVersions; // nothing to do
        }

        if ($initialVersion->getStamp() < $finalVersion->getStamp()) {
            $versions = self::sortAsc($versions);
        } else {
            $versions = self::sortDesc($versions);
            list($initialVersion, $finalVersion) = array($finalVersion, $initialVersion);
        }

        foreach ($versions as $version) {
            if (($version->getStamp() >= $initialVersion->getStamp()) && ($version->getStamp(
                    ) <= $finalVersion->getStamp())
            ) {
                $betweenVersions[] = $version;
            }
        }

        return $betweenVersions;
    }
}