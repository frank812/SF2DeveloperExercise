<?php

// src/AppBundle/Controller/GitController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GitController extends Controller
{
        // BR2. Application should support configuring a maximum number of users that can be specified in one request
        // BR3. Application should support configuring the max number of starred repos to display per user.
        const MAX_USERS_PER_REQUEST = 3;
        const MAX_REPOS_PER_USER = 15;

    /**
     * @Route("/starred")
     */
    public function fetchstarredAction(Request $request)
    {
        // get params
        $users = explode(',', $request->query->get('users'));

        //list most recent github repos starred by the specified users, merged together and sorted alphabetically by repo name.
        //each repo returned should display the user name, repo name, link, repo owner, and # of total stars (stargazers).
        $repos = array();
        $userCount = 0;
        foreach ($users as $username) {
            $reposForThisUser = array_slice($this->httpFetchStarredReposFromGit($username), 0, self::MAX_REPOS_PER_USER);
            // Each repo object in the array $reposForThisUser has many properties, but we'll use only:
            //      $repo->name;                     //repo name
            //      $repo->url;                      //link
            //      $repo->owner->login;             //repo owner
            //      $repo->stargazers_count;         //# of total stars (stargazers)

            // add user name to the list of fetched repos
            foreach ($reposForThisUser as $k => $repoObj) {
                $reposForThisUser[$k]->username = $username;
            }
            // add all the repos found to the main list
            $repos = array_merge($repos, $reposForThisUser);
            $userCount++;
            if ($userCount > self::MAX_USERS_PER_REQUEST) {
                break;
            }
        }

        // BR4. Allow the user to sort ascending and descending by each of the data fields - user name, repo name, repo owner and # of stars, and star date (if added)
        // -> not implemented (I'd sort in the client to avoid requests to the server).

        //sort the repo list by repo names
        usort($repos, array("AppBundle\Controller\GitController","sorterByName"));

        // build table data
        $tableWithStarredRepoList = array();
        foreach ($repos as $repo) {
            // add the repo to the table data
            $tableWithStarredRepoList[] = (object) array(
                "user" => $repo->username,
                "name" => $repo->name,
                "url" => $repo->url,
                "owner" => $repo->owner->login,
                "stars" => $repo->stargazers_count
            );
        }

        // send the table to the view
        return $this->render(
            'git/starred.html.twig',
            array('repos' => $tableWithStarredRepoList)
        );
    }

    /**
     * Get user info from Git
     * @param string $username
     */
    public function httpFetchStarredReposFromGit($username) {
        $url = 'https://api.github.com/users/' . $username . '/starred';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT,'Experimental/1');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
        $list = json_decode($response);
        curl_close($curl);

        // BR1. Application should handle the case when any of the specified users does not have a github account.
        // -> When the user doesn't have a github account or don't have any repos starred, we'll return an empty array
        return is_array($list) ? $list : array();
    }

    /**
     * Custom sorting function for the Repo object
     */
    function sorterByName($a, $b)
    {
        return strcmp($a->name, $b->name);
    }


}