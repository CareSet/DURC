<?php

return [

    /**
     * Hidden fields are database field names who's rows should be hidden on the
     * UPDATE and CREATE view of the UI. Fields from this list are written to an array in each controller
     * so they can be overridden. Only fields that are actually present in the model are written
     * to the model's controller.
     */
    'hidden_fields' => [
        'creation_date',
        'created_at_date',
        'createdAt',
        'created_at',
        'update_date',
        'update_at_date',
        'updated_date',
        'updated_at_date',
        'updated_at',
        'updatedAt',
        'update_at',
        'deleted_at',
        'deleted_date',
        'deletedAt',
    ],

    /**
     * If you want to disable the route to [baseUrl]/durctest, set this to false
     */
    'use_durctest_route' => true,

    /**
     * Date format to use for display (uses PHP formatting characters)
     */
    'date_format' => 'm/d/Y',
    'time_format' => 'H:i',
    'timezone' => 'America/Chicago',

];