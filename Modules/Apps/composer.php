<?php

view()->composer(
    [
        'apps::dashboard.layouts._aside',
        'apps::dashboard.index',
    ],
    \Modules\Apps\ViewComposers\Dashboard\StatisticsComposer::class
);
