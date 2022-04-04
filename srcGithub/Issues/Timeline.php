<?php

namespace Joomla\Github\Package\Issues;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Issues class for the Joomla Framework.
 *
 * @link   https://developer.github.com/v3/issues
 *
 * @since  __DEPLOY_VERSION__
 */
class Timeline extends AbstractPackage
{
	/**
	 * Get a single issue timeline.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 * @param   integer  $page     The page number from which to get items.
	 * @param   integer  $limit    The number of items on a page.
	 *
	 * @return  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function get($user, $repo, $issueId, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId . '/timeline';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)));
	}

	/**
	 * Get a single issue timeline.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 *
	 * @return  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getAll($user, $repo, $issueId)
	{
		$page  = 1;
		$limit = 100;

		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId . '/timeline';

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path, $page, $limit));
		$data     = $this->processResponse($response);

		while (array_key_exists('link', $response->headers) && strpos($response->headers['link'][0], 'rel="next"') !== false)
		{
			$page += 1;

			// Send the request.
			$response = $this->client->get($this->fetchUrl($path, $page, $limit));
			$data     = array_merge($data, $this->processResponse($response));
		}

		return $data;
	}
}
