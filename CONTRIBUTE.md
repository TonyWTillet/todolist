# Contributing to Todo List & Co

## Introduction
Welcome to the Todo List & Co project. This project is part of the OpenClassrooms training course.

This project is a web application that allows you to manage your tasks. You can create, edit, delete and mark tasks as done. You can also filter tasks by status (all, done, to do) and by category.

The project is developed with Symfony 7.1 and PHP 8.3. The database is managed with Doctrine.

To contribute to the project, you must follow the rules and guidelines described in this document. 

## Prerequisites && Installation
- Read the [Readme](README.md) file for the technical requirements.
- GitHub account.

## Getting started

### #1 Check issues
To report a bug, suggest an improvement, or discuss a new feature, use the [project issues](https://github.com/TonyWTillet/todolist/issues) section. Before creating a new issue, check that your topic has not already been covered by consulting existing issues. If this is a new topic, provide a clear and detailed description to help other contributors understand and resolve the issue.

### #2 Create an issue
1. Go to the Issues section: Go to your project's Issues page.

2. Click on "New Issue": At the top right of the page, click on the green "New Issue" button to open a new form.

3. Choose a tag : select the one that best suits your topic (bug, feature request, etc.).

4. Issue Title: Provide a clear and concise title that summarizes the issue or suggestion.

5. Detailed description: In the description field, explain your problem or suggestion in detail. Include steps to reproduce the bug, screenshots, or any other relevant details.

6. Submit the issue: Once everything is filled out, click "Submit new issue" to create the issue. It will then appear in the list of open issues, where it can be discussed and processed by the community.

### #3 Clone the repository
1. Clone the repository: To contribute to the project, you must first clone the repository. To do this, click on the green "Code" button at the top right of the repository page. Copy the URL that appears in the dropdown.
2. Open your terminal: navigate to the directory where you want to clone the repository.
3. Clone the repository: Run the following command in your terminal.
```git clone https://github.com/TonyWTillet/todolist.git ```

### #4 Create a new branch
1. Synchronize your local repository: Before creating a new branch, make sure your local repository is up to date with the main branch (main). Use the ```git pull origin main``` command to pull the latest changes.

2. Create a new branch: Use the ```git checkout -b``` command to create a new branch. It is important to name this branch consistently with the issue you are addressing. A good naming format is: issue-number-short-title. For example, for issue #42 which concerns a bug fix on the login form, you could name the branch issue-42-fix-login-form. ```git checkout -b issue-42-fix-login-form```

3. Verify that you are on the new branch: Use git branch to confirm that you are on the new branch.

4. Develop on this branch: Make your changes and commits on this branch. This helps keep your work well organized and specific to the issue you are dealing with.

5. Push your branch to the remote repository: Once your changes are complete, push the branch to the remote repository using ```git push origin branch-name```.

### #5 Quality control
If you have made changes to the code, it is important to ensure that your code meets the project's quality standards. You need to to complete the following rules:
-  Respect the PSR coding standard.
-  Respect the Symfony coding standard.
- Respect the structure of the project.
- Respect the naming conventions.
- Write unit tests for your code.
- Run the tests to ensure that your code works correctly.
- Use the code quality tools (PHPStan, Codacy, etc.) to ensure that your code is clean and maintainable.


### #6 Create a pull request
1. Go to the repository: Go to the repository page on GitHub.
2. Click on "Pull requests": At the top of the page, click on the "Pull requests" tab.
3. Click on "New pull request": Click on the green "New pull request" button.
4. Choose the branches: In the dropdowns, select the main branch as the base branch and your branch as the compare branch.
5. Fill in the pull request form: Provide a clear and concise title and description for your pull request. Include the issue number you are addressing in the title.
6. Submit the pull request: Once everything is filled out, click on the "Create pull request" button to submit your pull request. It will then appear in the list of open pull requests, where it can be reviewed and merged by the community.