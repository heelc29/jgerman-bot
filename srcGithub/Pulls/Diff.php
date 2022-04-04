<?php

namespace Joomla\Github\Package\Pulls;

use Joomla\Github\AbstractPackage;
use Joomla\Http\Exception\UnexpectedResponseException;

/**
 * GitHub API Pull Requests class for the Joomla Framework.
 *
 * @link   https://developer.github.com/v3/pulls
 *
 * @since  __DEPLOY_VERSION__
 */
class Diff extends AbstractPackage
{
	/**
	 * Get a single pull request diff.
	 *
	 * @param   string   $user    The name of the owner of the GitHub repository.
	 * @param   string   $repo    The name of the GitHub repository.
	 * @param   integer  $pullId  The pull request number.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  UnexpectedResponseException
	 */
	public function get($user, $repo, $pullId)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/pulls/' . (int) $pullId;

		$headers = [
			'Accept' => 'application/vnd.github.v3.diff'
		];

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path), $headers);

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error   = json_decode($response->body);
			$message = isset($error->message) ? $error->message : 'Invalid response received from GitHub.';

			throw new UnexpectedResponseException($response, $message, $response->code);
		}

		return $response->body;
	}
}
