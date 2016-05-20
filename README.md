# Developer Exercise

Included in this repo is scaffolding for a new [Symfony 2.8](http://symfony.com/) project, which you are free to use. To run this project:

```
$ cd ./SF2DeveloperExercise
$ wget https://getcomposer.org/composer.phar
# accept all the defaults when installing for the first time
$ php composer.phar install
$ php app/console server:run
```

Browse to http://localhost:8000 to see the working project scaffold.


## Step 1: Programming Exercise

Here you'll build a small application that displays the repos that specified users have starred on github.com

You can find the documentation for the relevant github api here: https://developer.github.com/v3/activity/starring/#list-repositories-being-starred

Note that this is a public api, and no authentication is necessary.

When you are done the programming exercise, commit all your work in git and push it up to your github.com account. You'll be required to send us the link to your work immediately after completion.


#### Acceptance Criteria:

* must accept browser requests at the following url:
  http://[ip:port]/starred
* users names will be specified via a comma separated list passed via query parameter, for example http://[ip:port]/starred?users=fabpot,weaverryan
* page should return a list most recent github repos starred by the specified users, merged together and sorted alphabetically by repo name.
* each repo returned should display the user name, repo name, link, repo owner, and # of total stars (stargazers).
* The application must be committed to your github account and include a README.md that answers the questions asked in the following section.
* Prioritize functionality over style and design. Don't spend too much time trying to make it look nice, or with browser compatibility. Bootstrap or similar templates are fine to use, as is unstyled HTML as necessary.

#### Additional Features

The following can also be completed if there is time remaining:

1. Application should handle the case when any of the specified users does not have a github account.
2. Application should support configuring a maximum number of users that can be specified in one request
3. Application should support configuring the max number of starred repos to display per user.
4. Allow the user to sort ascending and descending by each of the data fields - user name, repo name, repo owner and # of stars, and star date (if added)
5. Include the time and date the specified user starred each repo listed. (Look at the api docs for how to do get this info!).

## Step 2: Questions

Please answer each of the following questions within the README.md file of your application repo (and be sure to commit it!) Point form answers are fine, and you don't need more than a couple points per question.

0. If you did not use Symfony 2, please provide instructions on how others can run this application locally.

0. If you did not use Symfony 2, which framework did you chose to write this in and why?

0. How long did you work on this programming exercise for?
About 2 hours

0. Were there any parts of the exercise you were not able to complete due to time constraints? If you had more time, how would you improve this code base?
The extra features are not included because of time. Add some abstraction level (for example, the git fetch is currently hard-coded).

0. What websites did you use to help you complete this exercise? Please provide links if possible.
Stackoverflow to solve some CURL- and sorting-related issues.

0. How would you test this application?
I'd mock the fetch to Git in PHPUnit

0. Your product owner just decided we should only show 10 results per page, and allow the user to paginate though the results. How would you approach adding this feature to your application?
I'd add more logic con the client and have pagination parameters only available for ajax requests.

0. Looking forward, how would you optimize this application to support thousands of concurrent users? What problems would you expect high traffic load to cause with this application? What are some possible solutions for those problems?
I'd add a cache for the cURL.

0. Anything else you'd like us to know?
