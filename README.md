# Resource Central
This Resource Central will be used as a searchable storage system for all useful content that we come accross and want to have in one place. Examples include tutorials, books, lessons, etc.

###User Stories (US)
The User Stories are descriptions of functionality from the point of view of the end user (for more information, see http://www.mountaingoatsoftware.com/agile/user-stories). Here we have divided the user stories into three sections so that we can easily follow the progress. They have also been assigned importance points, which help us always work on the most important functionalities. The user stories are then further broken down into technical tasks, which you can see in the Issues section here on github.

ToDo:
* US2: As an administrator, I can update the details of existing resources. Importance = 10
* US3: As a guest, I can search for resources based on title, author, keywords, etc. Importance = 10
* US4: As a guest, I can see all existing resources in a paginated view. Importance = 10
* US5: As a developer, I can see a log file which has all the errors related to the system's operation (not errors related to data validation), so that I can be aware of problems and more easily debug them. Importance = 20
* US6: As an administrator, I can add multiple-element resources to the system. Importance = 10

Work in process:
* US1: As an administrator, I can add single-element resources to the system. Importance = 40

Done:
* US7: As an administrator, I can see specific error messages telling me if the data I tried to enter into the system are not compliant with the requirements.

###[PUNCH SHEET](https://github.com/linguisticteam/content-reference-central/issues/17)
Before you do any work, click the link above to go directly to the PUNCH SHEET. If your name is not in the list, then add it. Below your name Write a description of what you intend to work on, including if you are going to add something completely new. Replace the description with "None" when you're done.


###How we label versions

The version numbers follow the system x.y.z, where:  
x = a completely new version of the application where a new approach/interface or whatever is implemented  
y = an added single feature or a group of features, usually written in the form of user stories  
z = a bug fix

For example, version 1.0.0 might be released. Then version 1.1.0 would be released when a new user story is completed, immediately making available the new functionality to users. But then a bug is found. Version 1.1.1 would be released when the bug is fixed.

When y is incremented z is reset to zero. When x is incremented both y and z are set to zero.
