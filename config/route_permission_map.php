<?php

/**
 * Route Permission Map
 *
 * Maps menu permission_group names (lowercase) to route middleware permission entities.
 * Used by FixPermissionSyncSeeder and UserService to auto-map menu-group-style
 * permissions to the route-level permissions that middleware actually checks.
 *
 * Format: 'lowercase menu permission_group' => 'route entity'
 *
 * Example: When a user is assigned 'view tender setup', the system will also
 *          assign 'view tender' because 'tender setup' maps to 'tender'.
 */

return [

    // ---- Root sidebar menus (no children with routes) ----
    // These map the root-level permission_group to the route entity

    // Website Setup children
    'slider'                          => 'slider',
    'announcement'                    => 'announcement',
    'latest cpcb'                     => 'latest cpcb',
    'page'                            => 'page',
    'media'                           => 'media',
    'who is who setup'                => 'who is who',
    'head office setup'               => 'head office',
    'regional directorate setup'      => 'regional directorate',
    'contact detail'                  => 'contact detail',
    'social media'                    => 'social media',
    'government portal'               => 'government portal',
    'cpcb portal'                     => 'cpcb portal',
    'gallery event'                   => 'gallery event',
    'faq'                             => 'faq',
    'complaint'                       => 'complaint',
    'studies report'                  => 'studies report',
    'employee'                        => 'employee',
    'employee menu'                   => 'employee menu',
    'user'                            => 'user',
    'role'                            => 'role',
    'audit log'                       => 'audit log',
    'authentication log'              => 'authentication log',
    'site setting'                    => 'site setting',
    'environmental regulation'        => 'environmental regulation',

    // ---- Root menus that need mapping (group ≠ route entity) ----
    'circular setup'                  => 'circular',
    'directory setup'                 => 'directory',
    'menu setup'                      => 'menu',
    'annual report setup'             => 'annual report',
    'gallery setup'                   => 'photo gallery',
    'video gallery setup'             => 'video gallery',
    'epr portal setup'                => 'epr portal',
    'comment reports'                 => 'comment report',
    'ngt court cases'                 => 'ngt court case',
    'fortnightly reports'             => 'fortnightly report',
    'feedback (zone 1630)'            => 'feedback',
    'site settings'                   => 'site setting',

    // ---- Child menus (already match route) ----
    'job'                             => 'job',
    'job post'                        => 'job post',
    'recruitment announcement'        => 'recruitment announcement',
    'subject area'                    => 'subject area',
    'tender category'                 => 'tender category',
    'zonal office'                    => 'zonal office',
    'tender'                          => 'tender',
    'direction act type'              => 'direction act type',
    'direction type'                  => 'direction type',
    'direction subject'               => 'direction subject',
    'direction state'                 => 'direction state',
    'direction category'              => 'direction category',
    'direction issued to'             => 'direction issued to',
    'direction'                       => 'direction',
    'letters_issued'                  => 'letters_issued',
    'quality zone'                    => 'quality zone',
    'agra air quality'                => 'agra air quality',
    'environmental regulation detail' => 'environmental regulation detail',
    'information centers'             => 'information centers',
    'information center detail'       => 'information center detail',
    'publication category'            => 'publication category',
    'division'                        => 'division',
    'designation'                     => 'designation',
    'query form subject'              => 'query form subject',
    'complaint form subject'          => 'complaint form subject',

    // ---- Child menus with "parent > child" group pattern ----
    'technical report setup > technical report' => 'technical report',
    'publication setup > publications'          => 'publication',
    'environmental regulation > tab'            => 'environmental regulation',

    // ---- Parent container groups (map to first child route) ----
    'website setup'                   => 'homepage about',
    'job setup'                       => 'job',
    'technical report setup'          => 'technical report',
    'publication setup'               => 'publication',
    'tender setup'                    => 'tender',
    'direction setup'                 => 'direction',
    'agra air quality setup'          => 'agra air quality',
    'information center'              => 'information centers',
    'master setup'                    => 'designation',
    'authentication setup'            => 'user',
    'logs'                            => 'audit log',

    // Special: website dashboard (used in sidebar @can)
    'website dashboard'               => 'website dashboard',
];
