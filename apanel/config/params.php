<?php

return [
    // текущее рабочее приложение
    'currApp' => WEB_APP ? WEB_APP : API,

    // текущее рабочее приложение с версией API
    'currAppWithApiVersion' => WEB_APP ? WEB_APP : API . '\v' . API_VERSION,
];

