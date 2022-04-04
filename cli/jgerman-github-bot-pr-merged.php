<?php
/**
 * JGerman GitHub Bot based on the Joomla! Framework
 *
 * @copyright  Copyright (C) 2022 J!German (www.jgerman.de) All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (PHP_SAPI != 'cli')
{
	echo 'This script needs to be called via CLI!' . PHP_EOL;
	exit;
}

// Set error reporting for development
error_reporting(-1);

// Load the contstants
require dirname(__DIR__) . '/includes/constants.php';

// Ensure we've initialized Composer
if (!file_exists(ROOT_PATH . '/vendor/autoload.php'))
{
	exit(1);
}

require ROOT_PATH . '/vendor/autoload.php';

// Load the github base configuration
require dirname(__DIR__) . '/includes/github-base.php';

// Parse input options
$options = getopt('', ['date:', 'page:']);

$since = isset($options['date']) ? new \DateTime($options['date']) : false;
$page  = $options['page'] ?? null;

if (!$since)
{
	echo 'No start date!' . PHP_EOL;
	exit();
}

echo $githubApiHelper->checkClosedAndMergedTranslationIssuesList($since, $page) . PHP_EOL;
