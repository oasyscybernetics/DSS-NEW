<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Storage;

/**
 * Interface TimeSeriesResultsInterface
 * @package Xibo\Service
 */
interface TimeSeriesResultsInterface
{
    /**
     * Time series results constructor
     */
    public function __construct($object = null);

    /**
     * Get statistics array
     * @return array[array statData]
     */
    public function getArray();

    /**
     * Get next row
     * @return array|false
     */
    public function getNextRow();

    /**
     * Get total number of stats
     * @return integer
     */
    public function getTotalCount();

}