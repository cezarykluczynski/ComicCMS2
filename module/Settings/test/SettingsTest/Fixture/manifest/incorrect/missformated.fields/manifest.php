<?php
/**
 * Manifest with missing fields.
 */

return array(
    'settings_groups' => array(
        /** No "name" error. */
        array(),
        /** No "prefix" error. */
        array(
            'name' => 'Name',
            'prefix' => 'incorrect_prefix',
        ),
        array(
            'name' => 'Correct name',
            'prefix' => 'correct_prefix:',
            'settings' => array(
                /** No "name" error. */
                array(),
                /** No "label" error. */
                array(
                    'name' => 'name',
                ),
                /** Empty "name" error. */
                array(
                    'name' => '',
                ),
                /** Empty "label" error. */
                array(
                    'name' => 'name',
                    'label' => '',
                ),
                /** Invalid "name" error. */
                array(
                    'name' => false,
                ),
                /** Invalid "label" error. */
                array(
                    'name' => 'name',
                    'label' => false,
                ),
            ),
        ),
    ),
);