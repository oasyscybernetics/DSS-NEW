<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Report;


/**
 * Interface ReportInterface
 * @package Xibo\Report
 */
interface ReportInterface
{
    /**
     * Set factories
     * @param \Slim\Helper\Set $container
     * @return $this
     */
    public function setFactories($container);

    /**
     * Return the twig file name of the report form
     * Load the report form
     * @return string
     */
    public function getReportForm();

    /**
     * Return the twig file name of the report email template
     * @return string
     */
    public function getReportEmailTemplate();

    /**
     * Get chart script
     * @return string
     */
    public function getReportChartScript($results);

    /**
     * Populate form title and hidden fields
     * @return array
     */
    public function getReportScheduleFormData();

    /**
     * Set Report Schedule form data
     * @return array
     */
    public function setReportScheduleFormData();

    /**
     * Generate saved report name
     * @param $filterCriteria
     * @return string
     */
    public function generateSavedReportName($filterCriteria);

    /**
     * Return data to build chart of saved report
     * @param array $json
     * @param object savedReport
     * @return array
     */
    public function getSavedReportResults($json, $savedReport);

    /**
     * Return results
     * @param $filterCriteria
     * @return array
     */
    public function getResults($filterCriteria);

    /**
     * @return int
     */
    public function getUserId();

    /**
     * @param $userId
     * @return $this
     */
    public function setUserId($userId);
}