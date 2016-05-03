# blogs.magicjudges.org

This is the complete WordPress network running on blogs.magicjudges.org.

This repository is supposed to be the single source of truth. The master branch
is designed to be the code that also runs in production. Be careful what you
merge here.

## Setup the code and development workflow

To setup the code on your local environment, clone this repository inclusive all
submodules:

    git clone --recursive git@github.com:magicjudges/blogs.magicjudges.org.git

If you have already cloned it, but forgot to include the submodules, run the
following command:

    git submodule update --init --recursive

Due to the organization of submodules, some of the code is not in the main
repository. But above command cloned all of them. Make sure you are in the right
directory though for the following commands. For example, if you are modifying
the judge-familiar theme, first run this:

    cd wp-content/themes/judge-familiar

All development should happen in a fresh branch and request a merge
once completed and ready to be pushed to production.

Make a new branch:

    git checkout -b <feature-name>

You are now on your new branch. Once completed, commit all your code and push
to the repository:

    git push origin <feature-name>

If this command fails, most likely your RSA key does not have write access. Send
Joel a email with your **public** RSA key file to fix this.

The new branch should show up in GitHub. A green button "Compare & pull request"
should be visible. Create the request by describing what you have changed and
how you have verified that it works. Then wait for a merge.

## Resources

* New to Git? Try this [Git Tutorial](https://try.github.io/levels/1/challenges/1).
* New to WordPress? See this [introduction resources](https://codex.wordpress.org/Getting_Started_with_WordPress).
