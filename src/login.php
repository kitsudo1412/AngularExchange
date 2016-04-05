<?php
setcookie('__AE_TOKEN__', ae_encrypt(session_id()), time() + 24 * 3600);