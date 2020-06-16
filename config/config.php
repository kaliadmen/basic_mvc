<?php

    //set debug information
    define('DEBUG', true);

    //database driver
    define('DB_DRIVER', 'mysql');

    //database host *** use ip to avoid dns lookup
    define('DB_HOST', '127.0.0.1');

    //database name
    define('DB_NAME', 'mvc');

    //database username
    define('DB_USER', 'root');

    //database password
    define('DB_PASSWORD', '1qaz@WSX3edc$RFV');

    //default controller if there isnt one defined in the url
    define('DEFAULT_CONTROLLER', 'Home');

    //this layout is used if one isn't set in the controller
    define('DEFAULT_LAYOUT', 'default');

    //root directory
    define('PROJECTROOT', '/');

    //used if no site title is set
    define('SITE_TITLE', 'Tsudo MVC');

    //session name for logged in user
    define('CURRENT_USER_SESSION_NAME', 'xkwErsudiGLoDqtUryPKvX');

    //cookie name for logged in user
    define('REMEMBER_ME_COOKIE_NAME',   'erQjky54Gdbit0doxYw68P');

    //time in seconds for remember me cookie to live (30 days)
    define('REMEMBER_COOKIE_EXPIRE',   2592000);

