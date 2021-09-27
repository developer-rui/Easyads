<?php

include_once("../common/common.php");
include_once("../common/db.php");
include_once("login.php");

init_session();

logout_user();

to_page("../index.php");