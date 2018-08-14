<?php
	/******************************************************
	*   Author          Unbeleadsable <support@unbeleasable.com>
	*   Version         1.0.3
	*   Last modified   February 3rd, 2016
	*   Web             http://
	*   This is the main configuration file. It contains the
	*	configuration directives that gives the core and other
	*	files its' intructions.
	*******************************************************/
abstract class Config{
		#--------------------------- Website Properties ---------------------------#
		# Website url with the trailing slash
		CONST WEBSITE_URL = "http://localhost:8010/UnbeleadsableV1/app";

		# Website title / Name of the website
		CONST WEBSITE_TITLE = "Unbeleadsable";

        # The duration of the password reset key in hours (Default: 24)
        # Set to zero to have no expiration.
        CONST PASSWORD_RESET_KEY_DURATION = 24;

        # General field length requirement
		CONST MIN_GEN_FIELD_LENGTH = 5;
		CONST MAX_GEN_FIELD_LENGTH = 25;

        # Subject requirement
        CONST MIN_SUBJECT_LENGTH = 3;
        CONST MAX_SUBJECT_LENGTH = 50;

        # Level requirement
        CONST MIN_LEVEL_LENGTH = 1;
        CONST MAX_LEVEL_LENGTH = 50;

        # School requirement
        CONST MIN_SCHOOL_NAME_LENGTH = 5;
        CONST MAX_SCHOOL_NAME_LENGTH = 50;

        # Sponsor requirement
        CONST MIN_SPONSOR_NAME_LENGTH = 3;
        CONST MAX_SPONSOR_NAME_LENGTH = 100;
        CONST MIN_SPONSOR_LINK_LENGTH = 0;
        CONST MAX_SPONSOR_LINK_LENGTH = 255;

        # Course requirement
        CONST MIN_COURSE_NAME_LENGTH = 3;
        CONST MAX_COURSE_NAME_LENGTH = 50;
        CONST MIN_COURSE_LOCATION_LENGTH = 10;
        CONST MAX_COURSE_LOCATION_LENGTH = 150;

        # Course part requirement
        CONST MIN_COURSE_PART_TITLE_LENGTH = 3;
        CONST MAX_COURSE_PART_TITLE_LENGTH = 50;
        CONST MIN_COURSE_PART_DESCRIPTION_LENGTH = 5;
        CONST MIN_COURSE_PART_DURATION_LENGTH = 2;
        CONST MAX_COURSE_PART_DURATION_LENGTH = 150;
        CONST MAX_COURSE_PART_UPDATE_NOTE_LENGTH = 250;

        # Email Template requirement
        CONST MIN_EMAIL_NAME_LENGTH = 3;
        CONST MAX_EMAIL_NAME_LENGTH = 50;
        CONST MIN_EMAIL_SLUG_LENGTH = 3;
        CONST MAX_EMAIL_SLUG_LENGTH = 50;
        CONST MIN_EMAIL_CONTENT_LENGTH = 5;

        CONST INSTALLATION = 49.99;
		CONST SUBSCRIPTION = 99.99;
		CONST PTP_CURRENCY_CODE = "USD";

		#--------------------------------------------------------------------------#

        #--------------------------- Error Messages -------------------------------#

        # No permission error message
        CONST INVALID_PERMISSION_MESSAGE = "You do not have the permission to view the page you were trying to access.";

        # Unexpected database error message
        CONST UNEXPECTED_DB_ERROR = "An unexpected error has occurred. Please contact an administrator.";

		#--------------------------------------------------------------------------#

        #--------------------------- Core Properties ------------------------------#
		# Folder that contains the post actions without the trailing slash
		CONST POST_ACTION_PATH = "actions/post";

		# True to use version control (Default: true)
		# This will append the version to the post action path (Example: actions/post/1.0.0/)
		CONST INIT_POST_VERSION_CONTROL = true;

		# Default post action version for the core if post version control is enabled
		CONST DEFAULT_CORE_POST_VERSION = "1.0.0";

		# Folder that contains the get actions without the trailing slash
		CONST GET_ACTION_PATH = "actions/get";

		# True to use version control (Default: false)
		# This will append the version to the get action path (Example: actions/get/1.0.0/)
		CONST INIT_GET_VERSION_CONTROL = false;

		# Default get action version for the core if get version control is enabled
		CONST DEFAULT_CORE_GET_VERSION = "1.0.0";

		# True to use Tokenizer (Default: false)
		CONST INIT_TOKENIZER = true;

		# True to enable action case (Default: true)
		# Set to true if you want more than one action per file
		CONST INIT_ACTION_CASE = true;

        # True if you're debugging
        CONST DEBUG_CORE = true;

		#--------------------------------------------------------------------------#
    }
?>
