<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Application name
  |--------------------------------------------------------------------------
  |
  | The name of the application: this name will be used as title and as header
  | in the application
  |
  */

  "app_name" => "Shared Settings",

  /*
  |--------------------------------------------------------------------------
  | Email confirmation
  |--------------------------------------------------------------------------
  |
  | Set this flag to true if you want to force every new user (on signup)
  | to verify his email address
  |
  */

  "email_confirmation" => true,

  /*
  |--------------------------------------------------------------------------
  | Gracefully error handling
  |--------------------------------------------------------------------------
  |
  | Set this flag to true if you want the application to handle 404 and 401
  | error pages
  |
  */

  "handle_errors" => true,

  /*
  |--------------------------------------------------------------------------
  | User per page
  |--------------------------------------------------------------------------
  |
  | Set the number of users per page to show on admin users list page
  |
  */

  "users_per_page" => 15,

  /*
  |--------------------------------------------------------------------------
  | Groups per page
  |--------------------------------------------------------------------------
  |
  | Set the number of groups per page to show on admin groups list page
  |
  */

  "groups_per_page" => 15,

    /*
   |--------------------------------------------------------------------------
   | Captcha validation on signup
   |--------------------------------------------------------------------------
   |
   | Flag to enable/disable captcha validation on user signup
   |
   */

  "captcha_signup" => true
];