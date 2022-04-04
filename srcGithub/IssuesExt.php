<?php

namespace Joomla\Github\Package;

use Joomla\Github\AbstractPackage;
use Joomla\Github\Package\Issues;

/**
 * GitHub API Issues class for the Joomla Framework.
 *
 * @link   https://developer.github.com/v3/issues
 *
 * @since  __DEPLOY_VERSION__
 */
class IssuesExt extends Issues
{
	/**
	 * List issues for a repository.
	 *
	 * @param   string              $user       The name of the owner of the GitHub repository.
	 * @param   string              $repo       The name of the GitHub repository.
	 * @param   string              $milestone  The milestone number, 'none', or *.
	 * @param   string              $state      The optional state to filter requests by. [open, closed]
	 * @param   string              $assignee   The assignee name, 'none', or *.
	 * @param   string              $mentioned  The GitHub user name.
	 * @param   string              $labels     The list of comma separated Label names. Example: bug,ui,@high.
	 * @param   string              $sort       The sort order: created, updated, comments, default: created.
	 * @param   string              $direction  The list direction: asc or desc, default: desc.
	 * @param   \DateTimeInterface  $since      Only issues updated at or after this time are returned.
	 *
	 * @return  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getListByRepositoryAll(
		$user,
		$repo,
		$milestone = null,
		$state = null,
		$assignee = null,
		$mentioned = null,
		$labels = null,
		$sort = null,
		$direction = null,
		\DateTimeInterface $since = null,
	)
	{
		$page  = 1;
		$limit = 100;

		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues';

		$uri = $this->fetchUrl($path, $page, $limit);

		if ($milestone)
		{
			$uri->setVar('milestone', $milestone);
		}

		if ($state)
		{
			$uri->setVar('state', $state);
		}

		if ($assignee)
		{
			$uri->setVar('assignee', $assignee);
		}

		if ($mentioned)
		{
			$uri->setVar('mentioned', $mentioned);
		}

		if ($labels)
		{
			$uri->setVar('labels', $labels);
		}

		if ($sort)
		{
			$uri->setVar('sort', $sort);
		}

		if ($direction)
		{
			$uri->setVar('direction', $direction);
		}

		if ($since)
		{
			$uri->setVar('since', $since->format(\DateTime::RFC3339));
		}

		// Send the request.
		$response = $this->client->get($uri);
		$data     = $this->processResponse($response);

		while (array_key_exists('link', $response->headers) && strpos($response->headers['link'][0], 'rel="next"') !== false)
		{
			$page += 1;
			$uri->setVar('page', (int) $page);

			// Send the request.
			$response = $this->client->get($uri);
			$data     = array_merge($data, $this->processResponse($response));
		}

		return $data;
	}
}
